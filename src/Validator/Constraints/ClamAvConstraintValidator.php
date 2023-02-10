<?php

namespace App\Validator\Constraints;

use Exception;
use Psr\Log\LoggerInterface;
use Socket\Raw\Factory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Xenolope\Quahog\Client;
use Xenolope\Quahog\Result;

/**
 * Class ClamAvValidator.
 */
class ClamAvConstraintValidator extends ConstraintValidator
{
    private string $clamavUrl;
    private LoggerInterface $logger;
    private static int $MAX_RETRIES = 3;

    public function __construct(string $clamavUrl, LoggerInterface $logger)
    {
        $this->clamavUrl = $clamavUrl;
        $this->logger = $logger;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ClamAvConstraint) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ClamAv');
        }

        try {
            $scanResult = $this->scan($value, $constraint->chmod);
            if (!$scanResult) {
                return;
            }
            if (!$scanResult->isOk()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{reason}}', $scanResult->getReason())
                    ->addViolation();
            }
        } catch (Exception) {
            $this->context->buildViolation($constraint->errorMessage)
                ->addViolation();
        }
    }

    private function scan($value, $chmod, $retry = 0): ?Result
    {
        try {
            $socket = (new Factory())->createClient($this->clamavUrl);
            $client = new Client($socket, 1, \PHP_NORMAL_READ);
            $client->startSession();

            if (null === $value || '' === $value || !$client->ping()) {
                return null;
            }

            $path = $value instanceof File ? $value->getPathname() : (string) $value;

            @chmod($path, $chmod);

            $scanResult = $client->scanLocalFile($path);
            $client->endSession();

            return $scanResult;
        } catch (\Exception $exception) {
            if ($retry < static::$MAX_RETRIES) {
                $this->logger->warning(sprintf('Error scanning doc %s : %s, retrying', $value, $exception));

                return $this->scan($value, $chmod, $retry + 1);
            }
            $this->logger->error(sprintf(
                'Error scanning doc %s : %s, max retries exhausted', $value, $exception
            ));
            throw $exception;
        }
    }
}

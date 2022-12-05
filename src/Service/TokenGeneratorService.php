<?php

namespace App\Service;

use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordTokenComponents;

class TokenGeneratorService
{
    public function __construct(
        private string $signingKey)
    {
    }

    /**
     * Get a cryptographically secure token with it's non-hashed components.
     *
     * @param mixed  $userId   Unique user identifier
     * @param string $verifier Only required for token comparison
     */
    public function createToken(\DateTimeInterface $expiresAt, $userId, string $verifier = null): ResetPasswordTokenComponents
    {
        if (null === $verifier) {
            $verifier = $this->getRandomAlphaNumStr();
        }

        $selector = $this->getRandomAlphaNumStr();

        $encodedData = json_encode([$verifier, $userId, $expiresAt->getTimestamp()]);

        return new ResetPasswordTokenComponents(
            $selector,
            $verifier,
            $this->getHashedToken($encodedData)
        );
    }

    private function getHashedToken(string $data): string
    {
        return base64_encode(hash_hmac('sha256', $data, $this->signingKey, true));
    }

    public function getRandomAlphaNumStr(): string
    {
        $string = '';

        while (($len = \strlen($string)) < 20) {
            /** @var int<1, max> $size */
            $size = 20 - $len;

            $bytes = random_bytes($size);

            $string .= substr(
                str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

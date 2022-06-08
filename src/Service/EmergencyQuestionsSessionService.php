<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EmergencyQuestionsSessionService
{
    private readonly Serializer $serializer;

    public function __construct(private readonly RequestStack $requestStack)
    {
        $this->serializer = new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
    }

    public function storeQuestions(array $questions): void
    {
        $this->getSessionService()->set('emergencyQuestions', $this->serializer->serialize($questions, 'json'));
    }

    public function getStoredQuestions(): ?array
    {
        $data = $this->getSessionService()->get('emergencyQuestions');
        if (null === $data) {
            return null;
        }

        return $this->serializer->deserialize(
            $data,
            'App\Entity\EmergencyQualificationQuestion[]',
            'json',
        );
    }

    public function clearStoredQuestions(): void
    {
        $this->getSessionService()->remove('emergencyQuestions');
    }

    private function getSessionService(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}

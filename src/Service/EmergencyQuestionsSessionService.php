<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmergencyQuestionsSessionService
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function storeQuestions(array $questions): void
    {
        $this->getSessionService()->set('emergencyQuestions', $questions);
    }

    public function getStoredQuestions(): ?array
    {
        $data = $this->getSessionService()->get('emergencyQuestions');

        return null === $data ? null : $data;
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

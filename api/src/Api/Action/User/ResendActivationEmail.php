<?php

namespace App\Api\Action\User;

use App\Service\Request\RequestService;
use App\Service\User\ResendActivationEmailService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResendActivationEmail
{
    private ResendActivationEmailService $activationEmailService;

    public function __construct(ResendActivationEmailService $activationEmailService)
    {
        $this->activationEmailService = $activationEmailService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $email = RequestService::getField($request, 'email');

        $this->activationEmailService->resend($email);

        return new JsonResponse(['message' => 'Activation email sent']);
    }
}

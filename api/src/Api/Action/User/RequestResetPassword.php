<?php

namespace App\Api\Action\User;

use App\Service\User\RequestResetPasswordService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPassword
{
    private RequestResetPasswordService $resetPasswordService;

    public function __construct(RequestResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'Request reset password email sent']);
    }
}
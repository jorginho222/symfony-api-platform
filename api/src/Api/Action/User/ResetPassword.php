<?php

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ResetPasswordService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(Request $request): User
    {
        $userId = RequestService::getField($request, 'userId');
        $resetPasswordToken = RequestService::getField($request, 'resetPasswordToken');
        $password = RequestService::getField($request, 'password');

        return $this->resetPasswordService->reset($userId, $resetPasswordToken, $password);
    }
}
<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;

class ActivateAccountService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function activate(Request $request, string $id): User
    {
        $user = $this->userRepository->findOneInactiveByIdAndTokenOrFail(
            $id,
            RequestService::getField($request, 'token')
        );

        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
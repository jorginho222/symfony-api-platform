<?php

declare(strict_types=1);

namespace App\Security\Core\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        try {
            return $this->userRepository->findOneByEmailOrFail($username);
        } catch (UserNotFoundException $e) {
            throw new \Symfony\Component\Security\Core\Exception\UserNotFoundException(\sprintf('User %s not found', $username));
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of %s are not supported', \get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function upgradePassword(User $user, string $newHashedPassword): void
    {
        $user->setPassword($newHashedPassword);

        $this->userRepository->save($user);
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
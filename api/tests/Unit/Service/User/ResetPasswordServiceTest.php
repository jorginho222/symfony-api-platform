<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ResetPasswordService;

class ResetPasswordServiceTest extends UserServiceTestBase
{
    private ResetPasswordService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResetPasswordService($this->userRepository, $this->encoderService);
    }

    public function testResetPasswordService(): void
    {
        $user = new User('user', 'user@api.com');
        $resetPasswordToken = 'abcde';
        $password = 'new-password';

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdAndResetPasswordToken')
            ->with($user->getId(), $resetPasswordToken)
            ->willReturn($user);

        $user = $this->service->reset($user->getId(), $resetPasswordToken, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertNull($user->getResetPasswordToken());
    }

    public function testResetPasswordForNonExistingUser(): void
    {
        $user = new User('user', 'user@api.com');
        $resetPasswordToken = 'abcde';
        $password = 'new-password';

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdAndResetPasswordToken')
            ->with($user->getId(), $resetPasswordToken)
            ->willThrowException(new UserNotFoundException());

        $this->expectException(UserNotFoundException::class);

        $this->service->reset($user->getId(), $resetPasswordToken, $password);
    }
}
<?php

namespace App\Tests\Unit\Service\User;

use App\Exception\User\UserNotFoundException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Service\User\RequestResetPasswordService;
use Symfony\Component\Messenger\Envelope;

class RequestResetPasswordServiceTest extends UserServiceTestBase
{
    private RequestResetPasswordService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new RequestResetPasswordService($this->userRepository, $this->messageBus);
    }

    public function testRequestResetPassword(): void
    {
        $email = 'user@api.com';
        $user = new User('name', $email);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user);

        $message = $this
            ->getMockBuilder(UserRegisteredMessage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageBus
            ->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isType('object'), $this->isType('array'))
            ->willReturn(new Envelope($message));

        $this->service->send($email);
    }

    public function testRequestResetPasswordForNoExistingUser(): void
    {
        $email = 'user@api.com';

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn(new UserNotFoundException());

        $this->expectException(UserNotFoundException::class);

        $this->service->send($email);
    }
}
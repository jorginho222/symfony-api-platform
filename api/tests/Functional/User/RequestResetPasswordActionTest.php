<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class RequestResetPasswordActionTest extends UserTestBase
{
    public function testRequestResetPassword(): void
    {
        $payload = [
          'email' => 'pepe@api.com',
        ];

        self::$pepe->request(
        'POST',
            \sprintf('%s/request_reset_password', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testRequestResetPasswordForNonExistingEmail(): void
    {
        $payload = [
            'email' => 'non-existing@api.com',
        ];

        self::$pepe->request(
            'POST',
            \sprintf('%s/request_reset_password', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
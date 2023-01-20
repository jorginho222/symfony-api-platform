<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ChangePasswordActionTest extends UserTestBase
{
    public function testChangePassword(): void
    {
        $payload = [
            'oldPassword' => 'password',
            'newPassword' => 'new-password',
        ];

        $pepeId = $this->getPepeId()['id'];

        self::$pepe->request(
            'PUT',
            \sprintf('%s/%s/change_password', $this->endpoint, $pepeId),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testChangePasswordWithInvalidOldPassword(): void
    {
        $payload = [
            'oldPassword' => 'invalid-password',
            'newPassword' => 'new-password',
        ];

        $pepeId = $this->getPepeId()['id'];

        self::$pepe->request(
            'PUT',
            \sprintf('%s/%s/change_password', $this->endpoint, $pepeId),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
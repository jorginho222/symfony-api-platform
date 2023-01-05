<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordActionTest extends UserTestBase
{
    public function testResetPassword(): void
    {
        $pepeId = $this->getPepeId();

        $payload = [
            'resetPasswordToken'=> '123456',
            'password' => 'new-password',
        ];

        self::$pepe->request(
            'PUT',
            \sprintf('%s/02539fbe-b11c-404a-83ef-d7e0d361ab06/reset_password', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$pepe->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($pepeId, $responseData['id']);
    }
}
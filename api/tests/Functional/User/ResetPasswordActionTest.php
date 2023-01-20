<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordActionTest extends UserTestBase
{
    public function testResetPassword(): void
    {
        $pepeId = $this->getPepeId()['id'];

        $payload = [
            'resetPasswordToken'=> '123456',
            'password' => 'new-password',
        ];

        self::$pepe->request('PUT',
            \sprintf('%s/%s/reset_password', $this->endpoint, $pepeId),
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
<?php

namespace App\Tests\Functional\User;

use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginActionTest extends UserTestBase
{
    public function testLogin(): void
    {
        $payload = [
          'username' => 'pepe@api.com',
          'password' => 'password',
        ];

        self::$pepe->request(
            'POST',
            \sprintf('%s/login_check', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertInstanceOf(JWTAuthenticationSuccessResponse::class, $response);
    }
}
<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResendActivationEmailActionTest extends UserTestBase
{
    public function testResendActivationEmail(): void
    {
        $payload = [
            'email' => 'marcos@api.com'
        ];

        self::$marcos->request('POST',
            \sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$marcos->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testResendActicationEmailToActiveUser(): void
    {
        $payload = [
            'email' => 'pepe@api.com'
        ];

        self::$pepe->request('POST',
            \sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload),
        );

        $response = self::$pepe->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
    }
}
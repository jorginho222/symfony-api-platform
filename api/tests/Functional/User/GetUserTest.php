<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    public function testGetUser(): void
    {
        $pepeId = $this->getPepeId();
        self::$pepe->request('GET', \sprintf('%s/%s', $this->endpoint, $pepeId));

        $response = self::$pepe->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($pepeId, $responseData['id']);
        $this->assertEquals('pepe@api.com', $responseData['email']);
    }

    public function testGetAnotherUserData(): void
    {
        $pepeId = $this->getPepeId();

        self::$carlos->request('GET', \sprintf('%s/%s', $this->endpoint, $pepeId));

        $response = self::$carlos->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
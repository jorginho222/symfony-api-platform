<?php

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestBase extends WebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $pepe = null;
    protected static ?KernelBrowser $carlos = null;
    protected static ?KernelBrowser $marcos = null;

    protected function setUp(): void
    {
        if (self::$client === null) {
            self::$client = static::createClient();
            self::$client->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json'
            ]);
        }

        if (self::$pepe === null) {
            self::$pepe = clone self::$client;
            $this->createAuthenticatedUser(self::$pepe, 'pepe@api.com');
        }
        if (self::$carlos === null) {
            self::$carlos = clone self::$client;
            $this->createAuthenticatedUser(self::$carlos, 'carlos@api.com');
        }
        if (self::$marcos === null) {
            self::$marcos = clone self::$client;
            $this->createAuthenticatedUser(self::$marcos, 'marcos@api.com');
        }
    }

    // $client taken as reference
    private function createAuthenticatedUser(KernelBrowser &$client, string $email): void
    {
        $user = $this->getContainer()->get(UserRepository::class)->findOneByEmailOrFail($email);

        $token = $this
            ->getContainer()
            ->get(JWTTokenManagerInterface::class)
            ->create($user);

        $client->setServerParameters([
            'HTTP_Authorization' => \sprintf('Bearer %s', $token),
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/ld+json'
        ]);
    }

    protected function getResponseData(\Symfony\Component\HttpFoundation\Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return false|mixed
     * @throws Exception
     */
    protected function getPepeId()
    {
        return $this->initDbConnection()->query('SELECT id FROM user WHERE email = "pepe@api.com"')->fetchOne();
    }

    /**
     * @return false|mixed
     * @throws Exception
     */
    protected function getCarlosId()
    {
        return $this->initDbConnection()->query('SELECT id FROM user WHERE email = "carlos@api.com"')->fetchOne();
    }
}
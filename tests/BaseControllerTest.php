<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Enum\UserRoleEnum;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->setUpClient();
        $container = static::getContainer();
        $connection = $container->get('database_connection');

        // Przywróć stan bazy
        $connection->executeStatement('TRUNCATE TABLE "user" RESTART IDENTITY CASCADE');

        $connection->executeStatement(
            '
            INSERT INTO "user" 
                (created_by_id, updated_by_id, uuid, email, password, first_name, last_name, is_active, employee_number, roles) 
            VALUES 
                (1, 1, :uuid, :email, :password, :first_name, :last_name, true, :employee_number, :roles)
        ',
            [
                'uuid' => 'dd2f7b38-bf2d-47c0-8cb6-1894b348df12',
                'email' => 'admin@example.com',
                'password' => '$2y$13$RhqWYnUyvtQPj8AdBmNvrukVnmCFKLlOXMVlSg46ya36i1kRkbDaW',
                'first_name' => 'Admin',
                'last_name' => 'Systemowy',
                'employee_number' => '0001',
                'roles' => json_encode(
                    [
                        UserRoleEnum::USER->value,
                        UserRoleEnum::ADMIN->value
                    ]
                ),
            ]
        );
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown(); // zakończ kernel żeby nie było błędów w kolejnych testach
        parent::tearDown();
    }

    protected function response(): ?Response
    {
        return $this->client->getResponse();
    }

    protected function setUpClient(): void
    {
        self::ensureKernelShutdown();
        parent::setUp();
        $this->client = static::createClient();

        $this->client->disableReboot();
    }

    protected function request(string $method, string $uri, array $parameters = [], array $files = [], array $server = [], ?string $content = null): void
    {
        $this->client->request($method, $uri, $parameters, $files, $server, $content);
    }
}

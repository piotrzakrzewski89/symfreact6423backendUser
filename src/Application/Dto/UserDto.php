<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\Entity\User;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class UserDto
{
    public int $id;
    public string $email;
    public array $roles;
    public bool $isActive;

    public string $firstName;
    public string $lastName;

    public Uuid $uuid;
    public DateTimeImmutable $createdAt;
    public ?DateTime $updatedAt;
    public ?DateTimeImmutable $deletedAt;
    public ?DateTime $lastLogin;
    public string $employeeNumber;


    public function __construct(private User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->roles = $user->getRoles();
        $this->isActive = $user->isActive();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->uuid = $user->getUuid();
        $this->createdAt = $user->getCreatedAt();
        $this->updatedAt = $user->getUpdatedAt();
        $this->deletedAt = $user->getDeletedAt();
        $this->lastLogin = $user->getLastLoginAt();
        $this->employeeNumber = $user->getEmployeeNumber();
    }

    public static function fromEntities(array $users): array
    {
        return array_map(fn(User $user) => new self($user), $users);
    }
}

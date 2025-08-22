<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Dto\UserDto;
use App\Domain\Entity\Admin;
use App\Domain\Entity\User;

class UserFactory
{
    public function createFromDto(UserDto $dto, Admin $admin): User
    {
        $user = new User();
        $this->mapDtoToEntity($dto, $user);

        $user->setIsDeleted(false);
        $user->setCreatedBy($admin);

        return $user;
    }

    public function updateFromDto(UserDto $dto, User $user, Admin $admin): User
    {
        $this->mapDtoToEntity($dto, $user);
        $user->setIsDeleted(false);
        $user->setCreatedBy($admin);

        return $user;
    }

    private function mapDtoToEntity(UserDto $dto, User $user): void
    {
        $user
            ->setEmail($dto->email)
            ->setCompany($dto->company)
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setEmployeeNumber($dto->employeeNumber)
            ->setIsActive($dto->isActive);
    }
}

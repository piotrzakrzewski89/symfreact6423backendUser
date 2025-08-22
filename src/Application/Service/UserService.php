<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\UserDto;
use App\Application\Factory\UserFactory;
use App\Domain\Entity\Admin;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserMailer $userMailer,
        private EntityManagerInterface $em,
        private UserFactory $userFactory
    ) {}

    public function createUser(UserDto $dto, int $adminId): User
    {
        // Sprawdzenie unikalności email
        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            throw new \DomainException('Pracownik o tym adresie email już istnieje.');
        }

        // Sprawdzenie unikalności employeeNumber
        if ($this->userRepository->findOneBy(['employeeNumber' => $dto->employeeNumber])) {
            throw new \DomainException('Pracownik o takim numerze kadrowym już istnieje.');
        }

        $user = $this->userFactory->createFromDto($dto, $this->getAdmin($adminId));

        $this->em->persist($user);
        $this->em->flush();

        $this->userMailer->sendCreated($user);

        return $user;
    }

    public function updateUser(UserDto $dto, int $adminId): ?User
    {
        $user = $this->userRepository->find($dto->id);

        if (!$user) {
            return null;
        }

        // Sprawdzenie unikalności email - tylko jeśli zmieniono lub nowy email
        $existingByEmail = $this->userRepository->findOneBy(['email' => $dto->email]);
        if ($existingByEmail && $existingByEmail->getId() !== $user->getId()) {
            throw new \DomainException('Firma o tym adresie email już istnieje.');
        }

        // Sprawdzenie unikalności employeeNumber
        $existingByShortName = $this->userRepository->findOneBy(['employeeNumber' => $dto->employeeNumber]);
        if ($existingByShortName && $existingByShortName->getId() !== $user->getId()) {
            throw new \DomainException('Firma o tej krótkiej nazwie już istnieje.');
        }

        $user = $this->userFactory->updateFromDto($dto, $user, $this->getAdmin($adminId));

        $this->em->persist($user);
        $this->em->flush();

        $this->userMailer->sendUpdated($user);

        return $user;
    }

    public function changeActive(int $id, int $adminId): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return null;
        }

        $admin = $this->getAdmin($adminId);

        if ($user->isActive()) {
            $user->deactivate($admin);
        } else {
            $user->activate($admin);
        }

        $this->em->flush();

        $this->userMailer->sendChangeActive($user);

        return $user;
    }

    public function deleteCompany(int $id, int $adminId): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return null;
        }

        $admin = $this->getAdmin($adminId);
        $user->softDelete($admin);

        $this->em->flush();

        $this->userMailer->sendDeleted($user);

        return $user;
    }

    private function getAdmin(int $adminId): Admin
    {
        return $this->em->getReference(Admin::class, $adminId);
    }
}

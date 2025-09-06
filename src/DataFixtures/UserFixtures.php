<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use App\Domain\Enum\UserRoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // UUID systemowego admina firmy
        $userUuid = Uuid::fromString('00000000-0000-4000-8000-000000000001');

        // UUID firmy (zgodnie z CompanyFixtures)
        $companyUuid = Uuid::fromString('00000000-0000-4000-8000-000000000002');

        // Sprawdź, czy admin już istnieje
        $existingUser = $manager->getRepository(User::class)
            ->findOneBy(['uuid' => $userUuid]);

        if (!$existingUser) {
            $user = (new User())
                ->setUuid($userUuid)
                ->setCompanyUuid($companyUuid)
                ->setCreatedBy($userUuid)
                ->setUpdatedBy($userUuid)
                ->setEmail('admin@cms.local')
                ->setFirstName('Systemowy')
                ->setLastName('Administrator')
                ->setEmployeeNumber('0001')
                ->setRoles([UserRoleEnum::ADMIN_CMS->value])
                ->setIsActive(true)
                ->setIsDeleted(false);

            $manager->persist($user);
            $manager->flush();

            echo "Systemowy admin został utworzony.\n";
        } else {
            echo "Systemowy admin już istnieje, pomijam.\n";
        }
    }
}

<?php

declare(strict_types=1);

namespace App\UI\Http\Controller;

use OpenApi\Attributes as OA;
use App\Application\Dto\UserDto;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[OA\Get(
        path: '/api/list-users',
        summary: 'Lista użytkowników',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista użytkowników',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/UserDto')
                )
            )
        ]
    )]
    #[Route('/api/list-users', name: 'api_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->getAllUsers();

        return $this->json(UserDto::fromEntities($users));
    }

    #[OA\Get(
        path: '/api/new-users',
        summary: 'Tworzenie użytkownika',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tworzenie użytkownika',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/UserDto')
                )
            )
        ]
    )]
    #[Route('/api/new-user', name: 'api_user_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password'] ?? ''));
        $user->setFirstName($data['firstName'] ?? '');
        $user->setLastName($data['lastName'] ?? '');
        $user->setEmployeeNumber($data['employeeNumber'] ?? '');
        $user->setIsActive((bool)$data['isActive']);
        $user->setCreatedBy($this->em->getReference(User::class, 1));

        $this->em->persist($user);
        $this->em->flush();

        return $this->json(['saved' => 'ok'], 200);
    }
}

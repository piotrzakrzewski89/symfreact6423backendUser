<?php

declare(strict_types=1);

namespace App\UI\Http\Controller;

use App\Domain\Enum\UserRoleEnum;
use App\Domain\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordEncoder
    ) {}

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email | !$password) {
            return new JsonResponse(['error' => 'Email i haslo sa wymagane'], 400);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['error' => 'Uzytkownik po danym mailu nie istnieje'], 400);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Nie prawidlowe haslo'], 400);
        }

        if (!in_array(UserRoleEnum::ADMIN->value, $user->getRoles())) {
            return new JsonResponse(['error' => 'Brak dostępu - wymagana rola ADMIN'], 403);
        }

        return new JsonResponse([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'message' => 'Zalogowano poprawnie',
        ]);
    }
}

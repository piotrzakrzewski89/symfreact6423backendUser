<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Dto\UserDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class UserDtoFactory
{
    public function fromRequest(Request $request): UserDto
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Nieprawidłowe dane JSON w żądaniu');
        }

        // Wymagane pola - jeśli ich brak, rzuć wyjątek
        $requiredFields = [
            'email',
            'companyUuid',
            'firstName',
            'lastName',
            'employeeNumber',
            'isActive'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Brak wymaganego pola: $field");
            }
        }

        return new UserDto(
            $data['id'] ?? null,
            null,
            Uuid::fromString($data['companyUuid']),
            $data['email'],
            $data['firstName'],
            $data['lastName'],
            $data['employeeNumber'],
            null,
            $data['isActive'],
            null,
            null,
            null,
            null
        );
    }
}

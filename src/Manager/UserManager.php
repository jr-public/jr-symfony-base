<?php

namespace App\Manager;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function create(string $email, string $plainPassword, array $roles = [], bool $isVerified = false): User
    {
        $user = new User();
        return $this->hydrate($user, $email, $plainPassword, $roles, $isVerified);
    }
    public function hydrate(User $user, string $email, string $plainPassword, array $roles = [], bool $isVerified = false): User
    {
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setIsVerified($isVerified);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        return $user;
    }
}
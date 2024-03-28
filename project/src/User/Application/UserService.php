<?php
declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\Repository\RoleRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\Role;
use App\User\Infrastructure\Persistence\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository
    ) {
    }

    /**
     * @throws \RuntimeException
     */
    public function addRole(string $name, string $displayName): Role
    {
        $exists = $this->roleRepository->findOneByName($name);

        if ($exists) {
            throw new \RuntimeException('Role already exists');
        }

        $role = new Role();
        $role->setName($name);
        $role->setDisplayName($displayName);

        $this->roleRepository->save($role);

        return $role;
    }

    /**
     * @param string $email
     * @param string $password
     * @param array<int> $roles
     * @return User
     * @throws \RuntimeException
     */
    public function addUser(string $email, string $password, array $roles): User
    {
        $exists = $this->userRepository->findOneByIdentifier($email);

        if ($exists) {
            throw new \RuntimeException('User already exists');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $password
            )
        );

        $roles = $this->roleRepository->findByIds($roles);

        foreach ($roles as $role) {
            $user->addRole($role);
        }

        $this->userRepository->save($user);

        return $user;
    }

}

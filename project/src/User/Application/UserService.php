<?php
declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\Model\UserVO;
use App\User\Domain\Repository\RoleRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\Role;
use App\User\Infrastructure\Persistence\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly RoleRepositoryInterface     $roleRepository
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
     * @param UserVO $userVO
     * @return User
     */
    public function addUser(UserVO $userVO): User
    {
        $exists = $this->userRepository->findOneByIdentifier($userVO->getEmail());

        if ($exists) {
            throw new \RuntimeException('User already exists');
        }

        $user = new User();
        $user->setEmail($userVO->getEmail())
            ->setPassword($this->hasher->hashPassword(
                $user,
                $userVO->getPlainPassword()
            ))
            ->setName($userVO->getName())
            ->setSurname($userVO->getSurname())
            ->setPhone($userVO->getPhone());

        $this->userRepository->save($user);

        return $user;
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneByIdentifier($email);
    }
}

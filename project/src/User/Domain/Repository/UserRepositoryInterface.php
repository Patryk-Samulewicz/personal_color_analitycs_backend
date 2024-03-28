<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Infrastructure\Persistence\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserRepositoryInterface
{
    public function findOneByIdentifier($value): ?User;

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;

    public function findByIds(array $ids): array;

    public function save(User $user): void;

}

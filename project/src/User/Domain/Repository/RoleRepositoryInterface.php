<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Infrastructure\Persistence\Role;

interface RoleRepositoryInterface
{
    public function findOneByName($value): ?Role;

    public function findByIds(array $ids): array;

    public function save(Role $role): void;

}

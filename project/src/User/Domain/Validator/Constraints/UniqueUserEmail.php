<?php

namespace App\User\Domain\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class UniqueUserEmail extends Constraint
{
    public string $message = 'email.already_in_use';
}
<?php

namespace App\User\Domain\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class UniqueUserPhone extends Constraint
{
    public string $message = 'phone.already_in_use';
}
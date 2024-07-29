<?php

namespace App\User\Domain\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Infrastructure\Persistence\User;

class UniqueUserPhoneValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['phone' => $value]);

        if ($user) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

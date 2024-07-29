<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\User\Domain\Validator\Constraints as CustomAssert;

class UserVO
{
    #[Assert\NotBlank(message: 'Email should not be blank')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    #[CustomAssert\UniqueUserEmail]
    private ?string $email;

    #[Assert\NotBlank(message: 'Name should not be blank')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]{2,255}$/', message: 'Name should have at least 2 characters')]
    private ?string $name;

    #[Assert\NotBlank(message: 'Surname should not be blank')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]{2,255}$/', message: 'Surname should have at least 2 characters')]
    private ?string $surname;

    #[Assert\NotBlank(message: 'Phone should not be blank')]
    #[Assert\Regex(pattern: '/^[0-9]{9}$/', message: 'Phone number should have 9 digits')]
    #[CustomAssert\UniqueUserPhone]
    private ?string $phone;

    #[Assert\NotBlank(message: 'Password should not be blank')]
    #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters long')]
    private string $plainPassword;

    public function __construct(
        string $email,
        string $name,
        string $surname,
        string $phone,
        string $password
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->plainPassword = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}

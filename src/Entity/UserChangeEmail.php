<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AssertCustom;

class UserChangeEmail
{
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *  message = "E-Mail: '{{ value }}' jest niepoprawny!",
     *  checkMX = true
     * )
     * @AssertCustom\UniqueEmail()
     */
    private $plainEmail;

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainEmail(): string
    {
        return (string) $this->plainEmail;
    }

    public function setPlainEmail(string $plainEmail): self
    {
        $this->plainEmail = $plainEmail;

        return $this;
    }
}
<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class UserChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Hasło nie jest takie samo jak twoje obecne!"
     * )
     */
    private $oldPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 6,
     *  max = 4096,
     *  minMessage = "Hasło musi być dłuższe niż {{ limit }} znaków!",
     *  maxMessage = "Hasło nie może być dłuższe {{ limit }} znaków!"
     * )
     */
    private $plainPassword;

    public function getOldPassword(): string
    {
        return (string) $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword= $oldPassword;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return (string) $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
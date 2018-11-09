<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AssertCustom;

/**
 * Class MessageNew
 * @package App\Entity
 * Model, który przetrzymuje dane potrzebne do wysyłania nowych wiadomości
 */
class MessageNew
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 4,
     *  max = 50,
     *  minMessage = "Tytuł wiadomości musi być dłuższy niż {{ limit }} znaki!",
     *  maxMessage = "Tytuł wiadomości nie może być dłuższy niż {{ limit }} znaków!"
     * )
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 4,
     *  minMessage = "Treść wiadomości musi być dłuższa niż {{ limit }} znaki!"
     * )
     */
    private $content;

    /**
     * @Assert\NotBlank()
     * @AssertCustom\SearchUserByUsername()
     */
    private $toUser;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getToUser(): ?string
    {
        return $this->toUser;
    }

    public function setToUser(string $toUser): self
    {
        $this->toUser = $toUser;

        return $this;
    }
}
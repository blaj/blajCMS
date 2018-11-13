<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleCategoryAddNew
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 1,
     *  max = 255,
     *  minMessage = "Tytuł musi być dłuższy niż {{ limit }} znaki!",
     *  maxMessage = "Tytuł nie może być dłuższy niż {{ limit }} znaków!"
     * )
     */
    private $title;

    private $description;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
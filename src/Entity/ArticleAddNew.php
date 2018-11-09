<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleAddNew
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 4,
     *  max = 255,
     *  minMessage = "Tytuł musi być dłuższy niż {{ limit }} znaki!",
     *  maxMessage = "Tytuł nie może być dłuższy niż {{ limit }} znaków!"
     * )
     */
    private $title;

    /**
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Assert\NotBlank()
     * @Assert\Image(
     *  minWidth = 50,
     *  maxWidth = 500,
     *  minHeight = 50,
     *  maxHeight = 500,
     *  allowSquare = true,
     *  allowPortrait = false,
     *  allowLandscape = false
     * )
     */
    private $image;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}
<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserChangeAvatar
{
    /**
     * @Assert\Image(
     *  minWidth = 50,
     *  maxWidth = 200,
     *  minHeight = 50,
     *  maxHeight = 200,
     *  allowSquare = true,
     *  allowPortrait = false,
     *  allowLandscape = false
     * )
     */
    private $avatar;

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }
}
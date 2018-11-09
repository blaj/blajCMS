<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SearchUserByUsername extends Constraint
{
    public $message = "Taki użytkownik nie istnieje!";
}
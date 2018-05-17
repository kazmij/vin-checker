<?php

namespace App\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Policy extends Constraint
{
    public $message = 'Pojazd o numerze polisy "{{ string }}" jest już zarejestrowany w sytemie!';
}
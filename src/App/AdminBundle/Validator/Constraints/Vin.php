<?php

namespace App\AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Vin extends Constraint
{
    public $message = 'Pojazd o numerze VIN "{{ string }}" jest już zarejestrowany w sytemie!';
}
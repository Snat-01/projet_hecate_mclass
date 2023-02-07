<?php 

namespace App\Validator;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

#[\Attribute]
class PostalCodeConstraint extends Constraint
{
    public string $message = 'Le code postale "{{ string }}" contiens de mauvais carateres';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    public function validatedBy()
    {
        return static::class.'Validator';
    }
}
<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ClamAvConstraint extends Constraint
{
    public string $message = 'validation.document.virus';
    public string $errorMessage = 'validation.document.scan_error';

    public int $chmod = 0644;
}

<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class Address
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @package AppBundle\Validator\Constraint
 */
class Address extends Constraint
{

    public $message = 'Invalid address';
}

<?php

namespace AppBundle\Validator\Constraint;

use Component\Locator\AddressResolverException;
use Component\Locator\AddressResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class Address
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @package AppBundle\Validator\Constraint
 */
class AddressValidator extends ConstraintValidator
{

    /**
     * @var AddressResolverInterface
     */
    private $resolver;

    /**
     * AddressValidator constructor.
     *
     * @param AddressResolverInterface $resolver A AddressResolverInterface instance.
     */
    public function __construct(AddressResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated.
     * @param Constraint $constraint The constraint for the validation.
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Address) {
            throw new UnexpectedTypeException($value, Address::class);
        }

        if (trim($value) === '') {
            return;
        }

        try {
            $this->resolver->resolveAddress($value);
        } catch (AddressResolverException $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}

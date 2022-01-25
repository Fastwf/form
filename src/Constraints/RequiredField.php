<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * Constraint validator that verify that the value respect the HTML required constraint.
 * 
 * A valid value is a non null and non empty string.
 * 
 * This constraint will not verify the value type. The contraint can be used as first constraint or after StringField constraint.
 */
class RequiredField implements Constraint
{

    /**
     * A boolean indicating if the value can be null/empty string or not.
     *
     * @var boolean
     */
    protected $required;

    /**
     * The constraint to use to validate the value when the value is not null or empty.
     *
     * @var Constraint|null $constraint
     */
    protected $constraint;

    /**
     * Constructor.
     *
     * @param boolean $required true when the value must be defined.
     * @param Constraint|null $constraint the constraint to apply to the value when exists (null for no value control).
     */
    public function __construct($required = true, &$constraint = null)
    {
        $this->required = $required;
        $this->constraint = &$constraint;
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        if ($value !== null && $value !== '')
        {
            return $this->constraint === null ? null : $this->constraint->validate($node, $context);
        }
        else
        {
            return $this->required
                ? $context->violation($value, 'field-required', [])
                : null;
        }
    }

}

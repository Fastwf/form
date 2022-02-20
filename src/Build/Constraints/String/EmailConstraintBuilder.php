<?php

namespace Fastwf\Form\Build\Constraints\String;

use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Constraints\String\SplitModifier;
use Fastwf\Constraint\Constraints\String\EmailFormat;
use Fastwf\Form\Build\Constraints\String\StringConstraintBuilder;

/**
 * Builder for input[email] form control.
 */
class EmailConstraintBuilder extends StringConstraintBuilder
{

    /**
     * A flag that indicate if the implementation must create a 'multiple' constraint.
     *
     * @var boolean
     */
    protected $multiple = false;

    public function __construct()
    {
        parent::__construct();

        // Register the factory to allows to update the $multiple factory
        $this->setFactory('multiple', [$this, 'multipleFactory']);
    }

    /**
     * The factory callback to use that allows to set the multiple value from given parameters.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $multiple true to set as multiple, false otherwise.
     * @return void
     */
    public function multipleFactory($_1, $_2, $multiple)
    {
        $this->multiple = $multiple;

        return [
            self::ATTRS => ['multiple' => true],
        ];
    }

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Reset the multiple property
        $this->multiple = false;

        // Add the email constraint
        \array_push($this->constraints, new EmailFormat());
    }
    
    protected function getTransformConstraint($constraints)
    {
        return new StringField();
    }

    protected function buildConstraints()
    {
        if ($this->multiple)
        {
            // Build multiple constraint,
            //  Perform split modification and than each items will be validated with defined constraints.
            $constraint = new Chain(
                false,
                $this->constraints[0],
                new SplitModifier(
                    ",",
                    true,
                    self::chainConstraints(
                        \array_slice($this->constraints, 1), // Use all constraints after the StringField (the transform constraint at 0)
                        false
                    ),
                ),
            );
        }
        else
        {
            // Can be considered as standard input text.
            //  So the default build implementation is enough.
            $constraint = parent::buildConstraints();
        }

        return $constraint;
    }

}

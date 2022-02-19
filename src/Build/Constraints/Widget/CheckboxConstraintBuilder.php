<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Constraints\BooleanField;
use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Form\Build\Constraints\TransformConstraintBuilder;

/**
 * Builder for input[checkbox] form control.
 */
class CheckboxConstraintBuilder extends TransformConstraintBuilder
{

    protected function getTransformConstraint($constraints)
    {
        // Control the 'equals' constraint to know how to initialize constraints
        // When it's set and is not one of the positive values, the checkbox require to validate a string value.
        if (\array_key_exists('equals', $constraints) && !\in_array($constraints['equals'], BooleanField::POSITIVE_VALUES))
        {
            $constraint = new StringField();
        }
        else
        {
            $constraint = new BooleanField();
        }

        return $constraint;
    }

    protected function buildEntryConstraint()
    {
        if ($this->constraints[0] instanceof BooleanField)
        {
            // The transform constraint is a BooleanField, the global constraint must validate a boolean value (only)
            //  This is used for control <input type="checkbox" value="on" ...>
            $constraint = new Nullable(!$this->required, $this->constraints[0]);
        }
        else
        {
            // The checkbox must validate that the value is set and correspond to the reference value
            //  This is used for control <input type="checkbox" value="any string value" ...>
            //  => the build system is the same than the default [RequiredField(treu/false, transformConstraint, ...other)].
            $constraint = parent::buildEntryConstraint();
        }

        return $constraint;
    }

}

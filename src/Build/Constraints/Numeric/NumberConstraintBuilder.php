<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Constraints\DoubleField;
use Fastwf\Form\Constraints\IntegerField;
use Fastwf\Form\Build\Factory\NumberFactory;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[number,range] form control.
 */
class NumberConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new NumberFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        if (self::isDoubleConstraint($constraints))
        {
            // The step format match a float definition, the field must be converted to Double
            $constraint = new DoubleField();
        }
        else
        {
            // In other cases (no step, bad step format, ...), the value will be converted as Integer
            $constraint = new IntegerField();
        }

        return $constraint;
    }

}

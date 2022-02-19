<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Build\Factory\WeekFactory;
use Fastwf\Form\Constraints\Date\WeekField;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[week] form control.
 */
class WeekConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new WeekFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        return new WeekField();
    }

}

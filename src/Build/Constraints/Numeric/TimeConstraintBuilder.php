<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Build\Factory\TimeFactory;
use Fastwf\Form\Constraints\Time\TimeField;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[time] form control.
 */
class TimeConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new TimeFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        return new TimeField();
    }

}

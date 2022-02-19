<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Build\Factory\DateTimeFactory;
use Fastwf\Form\Constraints\Date\DateTimeField;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[datetime-local] form control.
 */
class DateTimeConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new DateTimeFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        return new DateTimeField();
    }

}

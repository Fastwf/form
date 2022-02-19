<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Build\Factory\DateFactory;
use Fastwf\Form\Constraints\Date\DateField;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[date] form control.
 */
class DateConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new DateFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        return new DateField();
    }

}

<?php

namespace Fastwf\Form\Build\Constraints\Numeric;

use Fastwf\Form\Build\Factory\MonthFactory;
use Fastwf\Form\Constraints\Date\MonthField;
use Fastwf\Form\Build\Constraints\Numeric\NumericConstraintBuilder;

/**
 * Base builder for input[month] form control.
 */
class MonthConstraintBuilder extends NumericConstraintBuilder
{

    protected function getNumericFactory()
    {
        return new MonthFactory();
    }

    protected function getTransformConstraint($constraints)
    {
        return new MonthField();
    }

}

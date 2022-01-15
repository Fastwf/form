<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Constraints\Number\Step;
use Fastwf\Form\Build\Factory\NumericFactory;
use Fastwf\Constraint\Constraints\Number\Maximum;
use Fastwf\Constraint\Constraints\Number\Minimum;

/**
 * The factory to use for 'number' and 'range' input type.
 */
class NumberFactory extends NumericFactory
{

    public function min($min)
    {
        return [
            ConstraintBuilder::CSTRT => new Minimum($min),
            ConstraintBuilder::ATTRS => ["min" => $min]
        ];
    }

    public function max($max)
    {
        return [
            ConstraintBuilder::CSTRT => new Maximum($max),
            ConstraintBuilder::ATTRS => ["max" => $max]
        ];
    }

    public function step($step, $constraints)
    {
        return [
            ConstraintBuilder::CSTRT => new Step($step, ArrayUtil::getSafe($constraints, 'min', 0)),
            ConstraintBuilder::ATTRS => ["step" => $step]
        ];
    }

}

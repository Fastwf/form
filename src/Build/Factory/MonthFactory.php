<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\ADateFactory;
use Fastwf\Form\Constraints\Date\StepMonth;

/**
 * Constraints factory for month input type.
 */
class MonthFactory extends ADateFactory
{

    /// Not required implementation

    protected function getViolationCode()
    {
        return null;
    }

    protected function stepToSeconds($step)
    {
        return null;
    }

    /// Required implementation

    protected function toDateTime($value)
    {
        return DateTimeUtil::getMonth($value);
    }

    protected function toString($date)
    {
        return $date->format('Y-m');
    }

    /// Overriding methods

    public function step($step, $constraints)
    {
        return [
            ConstraintBuilder::CSTRT => new StepMonth(
                $step,
                \array_key_exists('min', $constraints) ? $this->getDateTimeOf($constraints['min']) : null,
            ),
            ConstraintBuilder::ATTRS => ["step" => $step]
        ];
    }

}

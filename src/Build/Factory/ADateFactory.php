<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Constraints\Date\MaxDateTime;
use Fastwf\Form\Constraints\Date\MinDateTime;
use Fastwf\Form\Constraints\Date\StepDateTime;

/**
 * Base class that allows to generate date or datetime constraints.
 */
abstract class ADateFactory extends NumericFactory
{

    /**
     * According to the implementation, return the step violation code to use.
     *
     * @return string
     */
    protected abstract function getViolationCode();

    /**
     * Try to parse the value as datetime.
     *
     * @param string $value the date time to parse
     * @return \DateTime the parsed datetime.
     * @throws ValueError when parsing is not possible.
     */
    protected abstract function toDateTime($value);

    /**
     * Format the datetime as string.
     *
     * @param \DateTime $date the date to format.
     * @return string the date formatted.
     */
    protected abstract function toString($date);

    /**
     * Convert the step into seconds.
     *
     * @param mixed $step the step to convert.
     * @return int the number of seconds.
     */
    protected abstract function stepToSeconds($step);

    /**
     * Try to parse the value as datetime.
     *
     * @param string|\DateTime $value the date time to parse
     * @return \DateTime the parsed datetime.
     * @throws ValueError when parsing is not possible.
     */
    protected function getDateTimeOf($value)
    {
        if (!($value instanceof \DateTime))
        {
            $value = $this->toDateTime($value);
        }

        return $value;
    }

    public function min($min)
    {
        $date = $this->getDateTimeOf($min);

        return [
            ConstraintBuilder::CSTRT => new MinDateTime($date),
            ConstraintBuilder::ATTRS => ["min" => $this->toString($date)]
        ];
    }

    public function max($max)
    {
        $date = $this->getDateTimeOf($max);

        return [
            ConstraintBuilder::CSTRT => new MaxDateTime($date),
            ConstraintBuilder::ATTRS => ["max" => $this->toString($date)]
        ];
    }

    public function step($step, $constraints)
    {
        // Convert the step into second to allows to use step value like html attribute
        $stepSeconds = $this->stepToSeconds($step);

        if (\array_key_exists('min', $constraints))
        {
            $constraint = new StepDateTime($stepSeconds, $this->getDateTimeOf($constraints['min']), $this->getViolationCode());
        }
        else
        {
            $constraint = new StepDateTime($stepSeconds, null, $this->getViolationCode());
        }

        return [
            ConstraintBuilder::CSTRT => $constraint,
            ConstraintBuilder::ATTRS => ["step" => $step]
        ];
    }

}

<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Utils\DateIntervalUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Constraints\Time\MaxTime;
use Fastwf\Form\Constraints\Time\MinTime;
use Fastwf\Form\Constraints\Time\StepTime;
use Fastwf\Form\Build\Factory\NumericFactory;

/**
 * The factory to use for 'time' input type.
 */
class TimeFactory extends NumericFactory
{

    /**
     * Convert the time to date interval.
     *
     * @param \DateInterval|string $time the time to parse.
     * @return \DateInterval the given date interval or the parsed from time parameter
     */
    protected function getTime($time)
    {
        // Parse the value when it's required
        if ($time instanceof \DateInterval)
        {
            $interval = $time;
        }
        else
        {
            $interval = DateIntervalUtil::getTime($time);
            
            // Control the value parsed
            if ($interval === null)
            {
                throw new ValueError("Impossible to use the given time as parameter");
            }
        }

        return $interval;
    }

    public function min($min)
    {
        $interval = $this->getTime($min);

        return [
            ConstraintBuilder::CSTRT => new MinTime($interval),
            ConstraintBuilder::ATTRS => ["min" => DateIntervalUtil::formatTime($interval)]
        ];
    }

    public function max($max)
    {
        $interval = $this->getTime($max);

        return [
            ConstraintBuilder::CSTRT => new MaxTime($interval),
            ConstraintBuilder::ATTRS => ["max" => DateIntervalUtil::formatTime($interval)]
        ];
    }

    public function step($step, $constraints)
    {
        return [
            ConstraintBuilder::CSTRT => new StepTime(
                $step,
                \array_key_exists('min', $constraints) ? $this->getTime($constraints['min']) : null,
            ),
            ConstraintBuilder::ATTRS => ["step" => $step]
        ];
    }

}

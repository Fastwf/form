<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\Factory\ADateFactory;

/**
 * Constraints factory for week input type.
 */
class WeekFactory extends ADateFactory
{

    public function __construct()
    {
        parent::__construct('week');
    }

    protected function getViolationCode()
    {
        return 'step-week';
    }

    protected function toDateTime($value)
    {
        return DateTimeUtil::getWeek($value);
    }

    protected function toString($date)
    {
        return DateTimeUtil::formatIsoWeek($date);
    }

    protected function stepToSeconds($step)
    {
        return DateTimeUtil::WEEK_IN_SECONDS * $step;
    }

}

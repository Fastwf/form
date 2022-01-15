<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\Factory\ADateFactory;

/**
 * Factory responsible of date constraints.
 */
class DateFactory extends ADateFactory
{

    /**
     * The number of seconds in a day (24 * 60 * 60).
     */
    private const DAY_IN_SECONDS = 86400;

    protected function toDateTime($value)
    {
        return DateTimeUtil::getDateTime($value, DateTimeUtil::HTML_DATE_FORMAT);
    }

    protected function toString($date)
    {
        return $date->format(DateTimeUtil::HTML_DATE_FORMAT);
    }

    protected function stepToSeconds($step)
    {
        return self::DAY_IN_SECONDS * $step;
    }

}

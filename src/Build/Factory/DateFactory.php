<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\Factory\ADateFactory;

/**
 * Factory responsible of date constraints.
 */
class DateFactory extends ADateFactory
{

    protected function getViolationCode()
    {
        return 'step-date';
    }

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
        return DateTimeUtil::DAY_IN_SECONDS * $step;
    }

}

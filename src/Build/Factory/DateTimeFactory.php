<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\Factory\ADateFactory;

/**
 * Factory responsible of datetime local constraints.
 */
class DateTimeFactory extends ADateFactory
{

    protected function toDateTime($value)
    {
        return DateTimeUtil::getDateTime($value, DateTimeUtil::HTML_DATETIME_FORMAT);
    }

    protected function toString($date)
    {
        return $date->format(DateTimeUtil::HTML_DATETIME_FORMAT);
    }

    protected function stepToSeconds($step)
    {
        return $step;
    }

}

<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\Factory\ADateFactory;

/**
 * Factory responsible of datetime local constraints.
 */
class DateTimeFactory extends ADateFactory
{

    public function __construct()
    {
        parent::__construct('date-time');
    }

    protected function getViolationCode()
    {
        return 'step-datetime';
    }

    protected function toDateTime($value)
    {
        return DateTimeUtil::getDateTime($value, DateTimeUtil::AUTO_DATETIME_FORMAT);
    }

    protected function toString($date)
    {
        return DateTimeUtil::formatDateTime($date);
    }

    protected function stepToSeconds($step)
    {
        return $step;
    }

}

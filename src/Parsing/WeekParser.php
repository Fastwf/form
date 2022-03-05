<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\DateTimeUtil;

/**
 * HTML Week parser.
 * 
 * @inherits AParser<DateTime>
 */
class WeekParser extends AParser
{
    
    protected function valToStr($value, $_)
    {
        return DateTimeUtil::formatIsoWeek($value);
    }

    protected function strToVal($sequence, $_)
    {
        return DateTimeUtil::getWeek($sequence);
    }

}

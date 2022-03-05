<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\DateIntervalUtil;

/**
 * HTML Time parser.
 * 
 * @inherits AParser<DateInterval>
 */
class TimeParser extends AParser
{
    
    protected function valToStr($value, $_)
    {
        return DateIntervalUtil::formatTime($value);
    }

    protected function strToVal($sequence, $_)
    {
        return DateIntervalUtil::getTime($sequence);
    }

}

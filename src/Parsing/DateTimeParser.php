<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\DateTimeUtil;

/**
 * HTML DateTime parser.
 * 
 * @inherits AParser<DateTime>
 */
class DateTimeParser extends AParser
{
    
    protected function valToStr($value, $_)
    {
        return DateTimeUtil::formatDateTime($value);
    }

    protected function strToVal($sequence, $_)
    {
        return DateTimeUtil::getDateTime($sequence);
    }

}

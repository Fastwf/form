<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\DateTimeUtil;

/**
 * HTML Month parser.
 * 
 * @inherits AParser<DateTime>
 */
class MonthParser extends AParser
{
    
    protected function valToStr($value, $_)
    {
        return $value->format('Y-m');
    }

    protected function strToVal($sequence, $_)
    {
        return DateTimeUtil::getMonth($sequence);
    }

}

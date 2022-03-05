<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Exceptions\ParsingError;

/**
 * HTML Date parser.
 * 
 * @inherits AParser<DateTime>
 */
class DateParser extends AParser
{
    
    protected function valToStr($value, $_)
    {
        return $value->format(DateTimeUtil::HTML_DATE_FORMAT);
    }

    protected function strToVal($sequence, $_)
    {
        return DateTimeUtil::getDate($sequence);
    }

}

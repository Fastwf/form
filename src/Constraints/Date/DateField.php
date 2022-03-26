<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Constraints\Date\ADateTimeField;

/**
 * Date field constraint that control the date format validity and convert the value to \DateTime object.
 */
class DateField extends ADateTimeField
{

    public function __construct($format = DateTimeUtil::HTML_DATE_FORMAT)
    {
        parent::__construct($format);
    }

    protected function getName()
    {
        return 'field-date';
    }

    protected function parse($value)
    {
        return $value instanceof \DateTime
            ? $value
            : DateTimeUtil::getDate($value, $this->format);
    }

}

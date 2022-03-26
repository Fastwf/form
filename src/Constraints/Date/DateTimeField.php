<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Constraints\Date\ADateTimeField;

/**
 * DateTime field constraint that control the date time format validity and convert the value to \DateTime object.
 */
class DateTimeField extends ADateTimeField
{

    public function __construct($format = DateTimeUtil::AUTO_DATETIME_FORMAT)
    {
        parent::__construct($format);
    }

    protected function getName()
    {
        return 'field-datetime';
    }

    protected function parse($value)
    {
        return $value instanceof \DateTime
            ? $value
            : DateTimeUtil::getDateTime($value, $this->format);
    }

}

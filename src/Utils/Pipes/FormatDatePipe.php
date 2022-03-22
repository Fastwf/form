<?php

namespace Fastwf\Form\Utils\Pipes;

use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Interpolation\Api\Evaluation\PipeInterface;

/**
 * The format date pipe that allows to transform the date according to the datetime format.
 */
class FormatDatePipe implements PipeInterface
{

    public function transform($value, $arguments)
    {
        $type = $arguments[0];

        switch ($type)
        {
            case 'date':
                $out = $this->transformDate($value);
                break;
            case 'date-time':
                $out = $this->transformDateTime($value);
                break;
            case 'month':
                $out = $this->transformMonth($value);
                break;
            case 'week':
                $out = $this->transformWeek($value);
                break;
            default:
                throw new ValueError("Unexpected date type \"$type\"");
        }

        return $out;
    }

    /**
     * Allows to transform the datetime to date format.
     *
     * @param \DateTime $date the date to format.
     * @return string the stringified date.
     */
    public function transformDate($date)
    {
        return DateTimeUtil::formatDateTime($date, DateTimeUtil::HTML_DATE_FORMAT);
    }

    /**
     * Allows to transform the datetime to date time format.
     *
     * @param \DateTime $dateTime the date to format.
     * @return string the stringified date time.
     */
    public function transformDateTime($dateTime)
    {
        return str_replace('T', ' ', DateTimeUtil::formatDateTime($dateTime));
    }

    /**
     * Allows to transform the datetime to month format.
     *
     * @param \DateTime $month the date to format.
     * @return string the stringified month.
     */
    public function transformMonth($month)
    {
        return DateTimeUtil::formatDateTime($month, "Y-m");
    }

    /**
     * Allows to transform the datetime to iso week format.
     *
     * @param \DateTime $week the date to format.
     * @return string the stringified week.
     */
    public function transformWeek($week)
    {
        return DateTimeUtil::formatIsoWeek($week);
    }

}

<?php

namespace Fastwf\Form\Utils;

use Fastwf\Constraint\Utils\Range;
use Fastwf\Form\Utils\DateTimeUtil;

class DateIntervalUtil
{

    /**
     * The number of seconds in the minute.
     */
    public const MINUTE_IN_SECONDS = 60;

    /**
     * The number of seconds in 1 hour (60 * 60).
     */
    public const HOUR_IN_SECONDS = 3600;

    /**
     * Parse the time and convert it to date interval.
     *
     * @param string $time the time to parse as HTML time format.
     * @param boolean $isDayTime true to indicate that the time is in [00:00:00.000, 24:00:00:00.000[ interval, false for unlimited
     *                duration.
     * @return \DateInterval|null the date interval or null if parsing failed.
     */
    public static function getTime($time, $isDayTime = true)
    {
        $timeResult = null;

        $matches = [];
        if (\preg_match("/^(\\d+):(\\d{2})(?::(\\d{2})(?:\\.(\\d{3,6}))?)?$/", $time, $matches) === 1)
        {
            $timeResult = [(int) $matches[1], (int) $matches[2], 0, 0];

            // Set seconds and nano seconds
            $length = \count($matches);
            for ($group = 3; $group < $length; $group++)
            {
                $timeResult[$group - 1] = (int) sprintf("%-6d", $matches[$group]);
            }

            // It requires to verify the range of each fields when $isDayTime is set
            if ($isDayTime && !(
                    Range::inRange($timeResult[0], 0, 24)
                    && Range::inRange($timeResult[1], 0, 60)
                    && Range::inRange($timeResult[2], 0, 60)
                ))
            {
                // Validation failed -> set the result to null
                $timeResult = null;
            }
        }

        if ($timeResult === null)
        {
            return null;
        }
        else
        {
            // Convert the time result to DateInterval            
            $dateInterval = new \DateInterval(
                "PT" . $timeResult[0] . "H"  . $timeResult[1] . "M" . $timeResult[2] . "S"
            );
            $dateInterval->f = (int) sprintf("%-06s", $timeResult[3]);

            return $dateInterval;
        }
    }

    /**
     * Format the date interval as HTML time format.
     *
     * @param \DateInterval $interval the date interval to format.
     * @return string the date interval formatted (fraction ending 0 are trimmed).
     */
    public static function formatTime($interval)
    {
        // Correct the datetime to have on only nanoseconds to hours
        self::recalculate($interval, false);

        $suffix = "";
        if ($interval->f !== 0.0)
        {
            $format = '%H:%I:%S';

            $suffix = \rtrim(\sprintf(".%-06s", $interval->f), '0');
        }
        else if ($interval->s !== 0)
        {
            $format = '%H:%I:%S';
        }
        else
        {
            $format = '%H:%I';
        }

        return $interval->format($format) . $suffix;
    }

    /**
     * Convert the date interval to seconds.
     * 
     * For more precise result prevent to use month and year fields without days attribute set.
     * Else when computing a year have 365 days and month 30 days.
     *
     * @param \DateInterval $interval the date interval to use for convertion.
     * @return double the number of seconds
     */
    public static function toSeconds($interval)
    {
        // Start with days
        if ($interval->days !== false)
        {
            // According to the documentation, days is set when DateTime::diff function is called.
            //  The number of days is considered as more precise than compute days from days, months and years.
            $days = $interval->days;
        }
        else
        {
            // For simplification year = 365 days and month = 30 days
            $days = $interval->y * 365 + $interval->m * 30 + $interval->d;
        }

        return $days * DateTimeUtil::DAY_IN_SECONDS
            + $interval->h * self::HOUR_IN_SECONDS
            + $interval->i * self::MINUTE_IN_SECONDS
            + $interval->s
            + $interval->f / 1000000.0;
    }

    /**
     * Recalculate the date interval internal variable to have a duration members in correct range.
     *
     * @param \DateInterval $interval the date interval to recalculate.
     * @param boolean $days true to allows days in date interval, false push the rest in hours field.
     * @return void
     */
    public static function recalculate($interval, $days = true)
    {
        // Transform to seconds
        $seconds = self::toSeconds($interval);

        // modulus convert to integer, compute nanoseconds before any calcul
        $interval->f = $seconds * 1000000 % 1000000;

        $interval->y = 0;
        $interval->m = 0;

        if ($days)
        {
            $interval->d = intdiv($seconds, DateTimeUtil::DAY_IN_SECONDS);
            $seconds = $seconds % DateTimeUtil::DAY_IN_SECONDS;
        }
        else
        {
            $interval->d = 0;
        }

        $interval->h = intdiv($seconds, self::HOUR_IN_SECONDS);
        $seconds = $seconds % self::HOUR_IN_SECONDS;

        $interval->i = intdiv($seconds, self::MINUTE_IN_SECONDS);
        $seconds = $seconds % self::MINUTE_IN_SECONDS;

        $interval->s = $seconds;
    }

}

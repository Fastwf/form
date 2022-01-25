<?php

namespace Fastwf\Form\Utils;

use Fastwf\Constraint\Utils\Range;

/**
 * Date and DateTime util that help to parse date values
 */
class DateTimeUtil
{

    /**
     * The pattern of datetime-local expected.
     */
    private const HTML_DATETIME_PATTERN = "/^(\\d{4})-(\\d{2})-(\\d{2})T(\\d{2}):(\\d{2})(?::(\\d{2})(.\\d+)?)?$/";

    /**
     * The date format of input date fields.
     */
    public const HTML_DATE_FORMAT = "Y-m-d";

    /**
     * The datetime format of input datetime-local fields.
     */
    public const HTML_DATETIME_FORMAT = "Y-m-d\\TH:i";

    public const AUTO_DATETIME_FORMAT = 'auto';

    /**
     * The number of seconds in a day (24 * 60 * 60).
     */
    public const DAY_IN_SECONDS = 86400;

    /**
     * The number of seconds in a day (7 * 24 * 60 * 60).
     */
    public const WEEK_IN_SECONDS = 604800;

    /**
     * Try to parse the date using the format.
     *
     * @param string|null $value the datetime formatted
     * @param string $format the format of the value
     * @return \DateTime|null the value parsed or null (error or value null)
     */
    public static function getDate($value, $format = self::HTML_DATE_FORMAT)
    {
        // Control nullity of the value
        if ($value === null)
        {
            return null;
        }

        $parseResult = \date_parse_from_format($format, $value);

        if (empty($parseResult['errors']))
        {
            $date = new \DateTime();
            $date->setDate(
                $parseResult['year'],
                $parseResult['month'],
                $parseResult['day'],
            );
            $date->setTime(0, 0, 0, 0);
        }
        else
        {
            $date = null;
        }

        return $date;
    }

    /**
     * Parse the value as an HTML datetime.
     *
     * @param string $value the value to parse.
     * @return array the array containing the parsing result ("errors" is provided in case of invalid date time or format).
     */
    private static function getAutoDateTime($value)
    {
        $result = [];

        $matches = [];
        if (\preg_match(self::HTML_DATETIME_PATTERN, $value, $matches) === 1)
        {
            // Add the default for seconds and microseconds
            $corrected = \array_merge($matches, [6 => "0", 7 => ".0"]);

            $result['year'] = (int) $corrected[1];
            $result['month'] = (int) $corrected[2];
            $result['day'] = (int) $corrected[3];
            $result['hour'] = (int) $corrected[4];
            $result['minute'] = (int) $corrected[5];
            $result['second'] = (int) $corrected[6];
            $result['fraction'] = ((double) $corrected[7]);

            if (
                // 2 - Is a valid date
                !\checkdate($result['month'], $result['day'], $result['year'])
                // 3 - Check the time validity
                || !Range::inRange($result['hour'], 0, 23)
                || !Range::inRange($result['minute'], 0, 60)
                || !Range::inRange($result['second'], 0, 60)
            )
            {
                $result['errors'] = 'Invalid datetime';
            }
        }
        else
        {
            $result['errors'] = 'Invalid format';
        }

        return $result;
    }

    /**
     * Try to parse the datetime using the format.
     *
     * @param string|null $value the datetime formatted
     * @param string $format the format of the value
     * @return \DateTime|null the value parsed or null (error or value null)
     */
    public static function getDateTime($value, $format = self::AUTO_DATETIME_FORMAT)
    {
        // Control nullity of the value
        if ($value === null)
        {
            return null;
        }

        if (self::AUTO_DATETIME_FORMAT === $format)
        {
            $parseResult = self::getAutoDateTime($value);
        }
        else
        {
            $parseResult = \date_parse_from_format($format, $value);
        }

        if (empty($parseResult['errors']))
        {
            $dateTime = new \DateTime();
            $dateTime->setDate(
                $parseResult['year'],
                $parseResult['month'],
                $parseResult['day'],
            );
            $dateTime->setTime(
                $parseResult['hour'],
                $parseResult['minute'],
                $parseResult['second'],
                $parseResult['fraction'] * 1000000,
            );
        }
        else
        {
            $dateTime = null;
        }

        return $dateTime;
    }

    /**
     * Parse the month and convert to datetime.
     * 
     * The date correspond to the first day of the week.
     *
     * @param string $month the month to parse.
     * @return \DateTime|null the datetime corresponding to the month (example '2020-02' -> '2020-02-01 00:00:00.000') or null when the 
     *                        format is invalid.
     */
    public static function getMonth($month)
    {
        $datetime = null;

        $match = [];
        if (preg_match('/^(\d{4})-(\d{2})$/', $month, $match) === 1)
        {
            $month = (int) $match[2];

            // Even if php can parse a month equals to 13, wee don't authorize because the format is invalid
            if (1 <= $month && $month < 13)
            {
                $datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s.u', $match[1]."-" . $match[2] . "-01T00:00:00.000");
            }
        }
        
        return $datetime;
    }

    /**
     * Parse the week string and convert to datetime.
     *
     * @param string $week the week string to parse (must respect the HTML week format "<year>-W<week>")
     * @return \DateTime|null the datetime corresponding to the week (example '2022-W01' -> '2022-01-03 00:00:00.000') or null when format
     *                        is invalid
     */
    public static function getWeek($week)
    {
        $datetime = null;

        $match = [];
        if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $match) === 1)
        {
            $datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s.u', $match[1]."-01-01T00:00:00.000");

            // Analyse the week day to adjust the first week
            $dayOfWeek = (int) $datetime->format('N');

            // [3 - (dayOfWeek + 2) % 7] result in the number of days required to move to the first day of the week 
            $period = (3 - ($dayOfWeek + 2) % 7) * self::DAY_IN_SECONDS
                + (((int) $match[2]) - 1) * self::WEEK_IN_SECONDS;

            if ($period !== 0)
            {
                $datetime->setTimestamp($datetime->getTimestamp() + $period);
            }
        }

        return $datetime;
    }

    /**
     * Format the datetime as ISO Week format.
     *
     * @param \DateTime $datetime the datetime to format.
     * @return string
     */
    public static function formatIsoWeek($datetime)
    {
        $year = (int) $datetime->format('Y');
        $week = (int) $datetime->format('W');

        // When the week is 1 and the month is 12 -> the year must be corrected
        if ($week === 1)
        {
            $month = (int) $datetime->format('n');
            if ($month === 12)
            {
                $year += 1;
            }
        }
        
        // Return the correct string
        return \sprintf("%04d-W%02d", $year, $week);
    }

}

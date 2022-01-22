<?php

namespace Fastwf\Form\Utils;

/**
 * Date and DateTime util that help to parse date values
 */
class DateTimeUtil
{

    /**
     * The date format of input date fields.
     */
    const HTML_DATE_FORMAT = "Y-m-d";

    /**
     * The datetime format of input datetime-local fields.
     */
    const HTML_DATETIME_FORMAT = "Y-m-d\\TH:i";

    /**
     * Try to parse the date using the format.
     *
     * @param string|null $value the datetime formatted
     * @param string $format the format of the value
     * @return \DateTime|null the value parsed or null (error or value null)
     */
    public static function getDate($value, $format)
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
     * Try to parse the datetime using the format.
     *
     * @param string|null $value the datetime formatted
     * @param string $format the format of the value
     * @return \DateTime|null the value parsed or null (error or value null)
     */
    public static function getDateTime($value, $format)
    {
        // Control nullity of the value
        if ($value === null)
        {
            return null;
        }

        $parseResult = \date_parse_from_format($format, $value);

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
     * Parse the week string and convert to datetime.
     *
     * @param string $week the week string to parse (must respect the HTML week format "<year>-W<week>")
     * @return \DateTime|null the datetime corresponding to the week (example '2022-W01' -> '2022-01-01 00:00:00.000') or null when format
     *                        is invalid
     */
    public static function getWeek($week)
    {
        $datetime = null;

        $match = [];
        if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $match) === 1)
        {
            $datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s.u', $match[1]."-01-01T00:00:00.000");

            $intWeek = (int) $match[2];
            $datetime->add(new \DateInterval("P" . ($intWeek - 1) . "W"));
        }

        return $datetime;
    }

}

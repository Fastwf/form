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

}

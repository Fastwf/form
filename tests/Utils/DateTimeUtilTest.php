<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;

class DateTimeUtilTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDateNull()
    {
        $this->assertNull(DateTimeUtil::getDate(null, "Y-m-d"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDate()
    {
        $this->assertEquals(
            strtotime("2021-01-01"),
            DateTimeUtil::getDate("2021-01-01", "Y-m-d")->getTimestamp(),
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDateError()
    {
        $this->assertNull(DateTimeUtil::getDate("2021-01-test", "Y-m-d"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDateTimeNull()
    {
        $this->assertNull(DateTimeUtil::getDateTime(null, "Y-m-d\\TH:i"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDateTime()
    {
        $this->assertEquals(
            strtotime("2021-01-01 12:00"),
            DateTimeUtil::getDateTime("2021-01-01T12:00", "Y-m-d\\TH:i")->getTimestamp()
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetDateTimeError()
    {
        $this->assertNull(DateTimeUtil::getDateTime("2021-01-01 12h00", "Y-m-d\\TH:i"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetWeek()
    {
        // The date must correspond to the first day of the ISO week
        //  For the week 2020-W53, 2021-01-01 is friday, the monday is the 2020-12-28
        $this->assertEquals(
            "2020-12-28",
            DateTimeUtil::getWeek("2020-W53")->format(DateTimeUtil::HTML_DATE_FORMAT),
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testGetWeekError()
    {
        $this->assertNull(DateTimeUtil::getWeek("test"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testFormatIsoWeek()
    {
        $this->assertEquals("2020-W01", DateTimeUtil::formatIsoWeek(DateTimeUtil::getWeek("2020-W01"))); // -> the date is 2019-12-28
        $this->assertEquals("2022-W01", DateTimeUtil::formatIsoWeek(DateTimeUtil::getWeek("2022-W01"))); // -> the date is 2022-01-03
    }

}

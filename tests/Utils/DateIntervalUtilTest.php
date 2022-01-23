<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Utils\DateIntervalUtil;

class DateIntervalUtilTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testGetTime()
    {
        $interval = DateIntervalUtil::getTime("12:30:25.123456", true);

        $expected = new \DateInterval("P0D");
        $expected->h = 12;
        $expected->i = 30;
        $expected->s = 25;
        $expected->f = 123456;

        $this->assertEquals(
            $expected,
            $interval,
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testGetTimeOutOfDay()
    {
        $interval = DateIntervalUtil::getTime("30:00", false);

        $expected = new \DateInterval("P0D");
        $expected->h = 30;

        $this->assertEquals(
            $expected,
            $interval,
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testGetTimeNullOutOfDay()
    {
        $this->assertNull(DateIntervalUtil::getTime("30:00"));
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testToSeconds()
    {
        $this->assertEquals(DateTimeUtil::DAY_IN_SECONDS, DateIntervalUtil::toSeconds(new \DateInterval("PT24H")));
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testToSecondsNano()
    {
        $this->assertEquals(
            15.123,
            DateIntervalUtil::toSeconds(DateIntervalUtil::getTime("00:00:15.123"))
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testToSecondsWithDays()
    {
        $interval = (new \DateTime("2022-01-01"))->diff(new \DateTime("2022-02-01"));

        $this->assertEquals(31 * DateTimeUtil::DAY_IN_SECONDS, DateIntervalUtil::toSeconds($interval));
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testRecalculate()
    {
        $interval = DateIntervalUtil::getTime("30:00", false);
        DateIntervalUtil::recalculate($interval, false);

        $this->assertEquals(
            30,
            $interval->h,
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testRecalculateDays()
    {
        $interval = DateIntervalUtil::getTime("30:00", false);
        DateIntervalUtil::recalculate($interval);

        $this->assertEquals(
            1,
            $interval->d,
        );
        $this->assertEquals(
            6,
            $interval->h,
        );
    }

    /**
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testFormatTime()
    {
        $times = [
            "12:30",
            "12:30:45",
            "12:30:45.123",
            "12:30:45.123456",
        ];

        foreach ($times as $time)
        {
            $this->assertEquals(
                $time,
                DateIntervalUtil::formatTime(DateIntervalUtil::getTime($time)),
            );
        }
    }

}

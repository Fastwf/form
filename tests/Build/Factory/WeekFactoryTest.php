<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\WeekFactory;

class WeekFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMin()
    {
        $f = new WeekFactory();

        $constraints = $f->min('2021-W01');

        $this->assertEquals(
            ['min' => '2021-W01'],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMax()
    {
        $f = new WeekFactory();

        $week = '2022-W04';
        $constraints = $f->max(DateTimeUtil::getWeek($week));

        $this->assertEquals(
            ['max' => $week],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\WeekFactory
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testStep()
    {
        $f = new WeekFactory();

        $constraints = $f->step(10, []);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

}

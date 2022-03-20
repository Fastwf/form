<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\MonthFactory;

class MonthFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMin()
    {
        $f = new MonthFactory();

        $constraints = $f->min('2021-01');

        $this->assertEquals(
            ['min' => '2021-01'],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMax()
    {
        $f = new MonthFactory();

        $week = '2022-01';
        $constraints = $f->max(DateTimeUtil::getMonth($week));

        $this->assertEquals(
            ['max' => $week],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     */
    public function testStep()
    {
        $f = new MonthFactory();

        $constraints = $f->step(10, []);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\MonthFactory
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStepWithMin()
    {
        $f = new MonthFactory();

        $constraints = $f->step(10, ['min' => '2000-02']);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

}

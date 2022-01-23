<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Utils\DateIntervalUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\TimeFactory;

class TimeFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testMin()
    {
        $f = new TimeFactory();

        $constraints = $f->min('01:00');

        $this->assertEquals(
            ['min' => '01:00'],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testError()
    {
        $this->expectException(ValueError::class);

        $f = new TimeFactory();
        $f->min('1h00');
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Constraints\Time\MaxTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testMax()
    {
        $f = new TimeFactory();

        $time = '18:00:59';
        $constraints = $f->max(DateIntervalUtil::getTime($time));

        $this->assertEquals(
            ['max' => $time],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\TimeFactory
     * @covers Fastwf\Form\Constraints\Time\StepTime
     */
    public function testStep()
    {
        $f = new TimeFactory();

        $constraints = $f->step(10, []);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

}

<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\DateTimeFactory;

class DateTimeFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMin()
    {
        $f = new DateTimeFactory();

        $constraints = $f->min('2021-01-01T00:00');

        $this->assertEquals(
            ['min' => '2021-01-01T00:00'],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMax()
    {
        $f = new DateTimeFactory();

        $date = '2021-01-01T00:00';
        $constraints = $f->max(DateTimeUtil::getDate($date, DateTimeUtil::HTML_DATETIME_FORMAT));

        $this->assertEquals(
            ['max' => $date],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateTimeFactory
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\ArrayUtil
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testStep()
    {
        $f = new DateTimeFactory();

        $constraints = $f->step(10, ['min' => '2021-01-01T00:00']);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

}

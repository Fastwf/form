<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\DateFactory;

class DateFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMin()
    {
        $f = new DateFactory();

        $constraints = $f->min('2021-01-01');

        $this->assertEquals(
            ['min' => '2021-01-01'],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     * @covers Fastwf\Form\Utils\DateTimeUtil
     */
    public function testMax()
    {
        $f = new DateFactory();

        $date = '2022-01-01';
        $constraints = $f->max(DateTimeUtil::getDate($date, DateTimeUtil::HTML_DATE_FORMAT));

        $this->assertEquals(
            ['max' => $date],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\ADateFactory
     * @covers Fastwf\Form\Build\Factory\DateFactory
     * @covers Fastwf\Form\Constraints\Date\StepDateTime
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testStep()
    {
        $f = new DateFactory();

        $constraints = $f->step(10, []);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

}

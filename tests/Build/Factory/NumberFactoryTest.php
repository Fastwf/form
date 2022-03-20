<?php

namespace Fastwf\Tests\Build\Factory;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Build\Factory\NumberFactory;

class NumberFactoryTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     */
    public function testMin()
    {
        $f = new NumberFactory();

        $constraints = $f->min(10);

        $this->assertEquals(
            ['min' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     */
    public function testMax()
    {
        $f = new NumberFactory();

        $constraints = $f->max(10);

        $this->assertEquals(
            ['max' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }

    /**
     * @covers Fastwf\Form\Build\Factory\NumberFactory
     * @covers Fastwf\Form\Build\Factory\NumericFactory
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testStep()
    {
        $f = new NumberFactory();

        $constraints = $f->step(10, []);

        $this->assertEquals(
            ['step' => 10],
            $constraints[ConstraintBuilder::ATTRS],
        );
    }
    
}

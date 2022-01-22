<?php

namespace Fastwf\Tests\Constraints\Number;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Constraints\Date\StepMonth;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class StepMonthTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     */
    public function testValidStep()
    {
        $constraint = new StepMonth(3);
        $node = Node::from(['value' => new \DateTime('2022-01-01')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     */
    public function testValidFrom()
    {
        $constraint = new StepMonth(3, new \DateTime('2022-01-01'));
        $node = Node::from(['value' => new \DateTime('2022-07-01')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     */
    public function testInvalid()
    {
        $constraint = new StepMonth(3, new \DateTime('2022-01-01'));
        $node = Node::from(['value' => new \DateTime('2022-09-01')]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\StepMonth
     */
    public function testConstructorException()
    {
        $this->expectException(ValueError::class);

        $constraint = new StepMonth(0);
    }

}

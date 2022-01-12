<?php

namespace Fastwf\Tests\Constraints\Number;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Constraints\Number\Step;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class StepTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testValidStep()
    {
        $constraint = new Step(2);
        $node = Node::from(['value' => 10]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testValidFrom()
    {
        $constraint = new Step(2.5, -0.5);
        $node = Node::from(['value' => -0.5 + 4 * 2.5]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testInvalid()
    {
        $constraint = new Step(2.5, -0.5);
        $node = Node::from(['value' => 10]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Number\Step
     */
    public function testConstructorException()
    {
        $this->expectException(ValueError::class);

        $constraint = new Step(0);
    }

}

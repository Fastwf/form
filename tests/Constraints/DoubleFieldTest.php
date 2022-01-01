<?php

namespace Fastwf\Tests\Constraints;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\DoubleField;
use Fastwf\Constraint\Api\ValidationContext;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class DoubleFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\DoubleField
     */
    public function testValidInteger()
    {
        $constraint = new DoubleField();
        $node = Node::from(['value' => '20']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(20.0, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\DoubleField
     */
    public function testValidDouble()
    {
        $constraint = new DoubleField();
        $node = Node::from(['value' => '3.14']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(3.14, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\DoubleField
     */
    public function testValidDoubleNoDecimal()
    {
        $constraint = new DoubleField();
        $node = Node::from(['value' => '3.']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(3.0, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\DoubleField
     */
    public function testInvalid()
    {
        $constraint = new DoubleField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

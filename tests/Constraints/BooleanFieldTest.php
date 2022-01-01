<?php

namespace Fastwf\Tests\Constraints;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\BooleanField;
use Fastwf\Constraint\Api\ValidationContext;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class BooleanFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\BooleanField
     */
    public function testValidTrue()
    {
        $constraint = new BooleanField();
        $node = Node::from(['value' => 'on']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(true, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\BooleanField
     */
    public function testValidFalse()
    {
        $constraint = new BooleanField();
        $node = Node::from(['value' => 'off']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(false, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\BooleanField
     */
    public function testInvalid()
    {
        $constraint = new BooleanField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

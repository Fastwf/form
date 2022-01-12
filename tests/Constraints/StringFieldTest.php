<?php

namespace Fastwf\Tests\Constraints;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class StringFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\StringField
     */
    public function testValidNotEmpty()
    {
        $constraint = new StringField();
        $node = Node::from(['value' => 'test']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals('test', $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\StringField
     */
    public function testValidNull()
    {
        $constraint = new StringField();
        $node = Node::from(['value' => null]);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals('', $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\StringField
     */
    public function testInvalid()
    {
        $constraint = new StringField();
        $node = Node::from(['value' => 123]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

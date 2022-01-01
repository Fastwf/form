<?php

namespace Fastwf\Tests\Constraints;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\IntegerField;
use Fastwf\Constraint\Api\ValidationContext;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class IntegerFieldTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\IntegerField
     */
    public function testValid()
    {
        $constraint = new IntegerField();
        $node = Node::from(['value' => '20']);

        $this->assertNull($constraint->validate($node, $this->context));
        $this->assertEquals(20, $node->get());
    }

    /**
     * @covers Fastwf\Form\Constraints\IntegerField
     */
    public function testInvalid()
    {
        $constraint = new IntegerField();
        $node = Node::from(['value' => 'test']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

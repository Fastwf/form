<?php

namespace Fastwf\Tests\Constraints\String;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class EqualsTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\String\Equals
     */
    public function testValid()
    {
        $constraint = new Equals("on");
        $node = Node::from(['value' => 'on']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\String\Equals
     */
    public function testInvalid()
    {
        $constraint = new Equals("on");
        $node = Node::from(['value' => 'off']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

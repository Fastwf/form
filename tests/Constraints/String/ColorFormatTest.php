<?php

namespace Fastwf\Tests\Constraints\String;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\String\ColorFormat;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class ColorFormatTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\String\ColorFormat
     */
    public function testValid()
    {
        $constraint = new ColorFormat();
        $node = Node::from(['value' => '#FF0000']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\String\ColorFormat
     */
    public function testInvalid()
    {
        $constraint = new ColorFormat();
        $node = Node::from(['value' => 'not color']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }
}

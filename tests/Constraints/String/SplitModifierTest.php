<?php

namespace Fastwf\Tests\Constraints\String;

use Fastwf\Constraint\Data\Node;
use Fastwf\Constraint\Constraints\Required;
use Fastwf\Tests\Constraints\ConstraintTestCase;
use Fastwf\Form\Constraints\String\SplitModifier;
use Fastwf\Constraint\Constraints\String\EmailFormat;

class SplitModifierTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\String\SplitModifier
     */
    public function testValid()
    {
        $constraint = new SplitModifier(",", true, new Required(true));
        $node = Node::from(['value' => 'one, two, three']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\String\SplitModifier
     */
    public function testValidNoTrim()
    {
        $constraint = new SplitModifier(",", false, new Required(true));
        $node = Node::from(['value' => 'one, two, three']);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\String\SplitModifier
     */
    public function testInvalid()
    {
        $constraint = new SplitModifier(",", false, new EmailFormat());
        $node = Node::from(['value' => 'invalid, data']);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

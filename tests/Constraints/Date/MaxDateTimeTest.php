<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\MaxDateTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class MaxDateTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     */
    public function testValid()
    {
        $constraint = new MaxDateTime(new \DateTime('2022-02-01'));
        $node = Node::from(['value' => new \DateTime('2022-01-10T12:00')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\MaxDateTime
     */
    public function testInvalid()
    {
        $constraint = new MaxDateTime(new \DateTime('2021-01-01'));
        $node = Node::from(['value' => new \DateTime('2022-01-01T00:00')]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

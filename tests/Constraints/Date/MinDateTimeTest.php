<?php

namespace Fastwf\Tests\Constraints\Date;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Constraints\Date\MinDateTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class MinDateTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     */
    public function testValid()
    {
        $constraint = new MinDateTime(new \DateTime('2022-01-01'), 'date');
        $node = Node::from(['value' => new \DateTime('2022-01-10T12:00')]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Date\MinDateTime
     */
    public function testInvalid()
    {
        $constraint = new MinDateTime(new \DateTime('2022-01-01'), 'date');
        $node = Node::from(['value' => new \DateTime('2021-01-01T00:00')]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

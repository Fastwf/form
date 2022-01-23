<?php

namespace Fastwf\Tests\Constraints\Time;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Utils\DateIntervalUtil;
use Fastwf\Form\Constraints\Time\MaxTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class MaxTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Time\MaxTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValid()
    {
        $constraint = new MaxTime(DateIntervalUtil::getTime("14:00"));
        $node = Node::from(['value' => DateIntervalUtil::getTime("14:00")]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\MaxTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testInvalid()
    {
        $constraint = new MaxTime(DateIntervalUtil::getTime("14:00"));
        $node = Node::from(['value' => DateIntervalUtil::getTime("15:00")]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

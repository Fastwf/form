<?php

namespace Fastwf\Tests\Constraints\Time;

use Fastwf\Constraint\Data\Node;
use Fastwf\Form\Utils\DateIntervalUtil;
use Fastwf\Form\Constraints\Time\MinTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class MinTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValid()
    {
        $constraint = new MinTime();
        $node = Node::from(['value' => DateIntervalUtil::getTime("00:00")]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\MinTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testInvalid()
    {
        $constraint = new MinTime(DateIntervalUtil::getTime("07:00"));
        $node = Node::from(['value' => DateIntervalUtil::getTime("06:00")]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

}

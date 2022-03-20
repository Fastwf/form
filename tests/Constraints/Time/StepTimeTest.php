<?php

namespace Fastwf\Tests\Constraints\Time;

use Fastwf\Constraint\Data\Node;
use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Form\Constraints\Time\StepTime;
use Fastwf\Tests\Constraints\ConstraintTestCase;

class StepTimeTest extends ConstraintTestCase
{

    /**
     * @covers Fastwf\Form\Constraints\Time\StepTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValidStep()
    {
        $constraint = new StepTime(60);
        $node = Node::from(['value' => new \DateInterval("PT1H21M")]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\StepTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testValidFrom()
    {
        $constraint = new StepTime(60, new \DateInterval("PT1H"));
        $node = Node::from(['value' => new \DateInterval("PT1H21M")]);

        $this->assertNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\StepTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testInvalid()
    {
        $constraint = new StepTime(60, new \DateInterval("PT1H30S"));
        $node = Node::from(['value' => new \DateInterval("PT1H21M")]);

        $this->assertNotNull($constraint->validate($node, $this->context));
    }

    /**
     * @covers Fastwf\Form\Constraints\Time\StepTime
     * @covers Fastwf\Form\Utils\DateIntervalUtil
     */
    public function testConstructorException()
    {
        $this->expectException(ValueError::class);

        $constraint = new StepTime(0);
    }

}

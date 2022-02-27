<?php

namespace Fastwf\Tests\Utils;

use Fastwf\Form\Utils\EntityUtil;
use PHPUnit\Framework\TestCase;

class EntityUtilTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Utils\EntityUtil
     */
    public function testSynchronizeMultiple()
    {
        $attrs = [];

        EntityUtil::synchronizeMultiple(true, $attrs);
        $this->assertTrue(\array_key_exists('multiple', $attrs));

        EntityUtil::synchronizeMultiple(false, $attrs);
        $this->assertFalse(\array_key_exists('multiple', $attrs));
    }

}

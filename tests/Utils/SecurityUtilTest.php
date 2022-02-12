<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\SecurityUtil;

class SecurityUtilTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Utils\SecurityUtil
     */
    public function testRandomToken()
    {
        $this->assertNotEquals(
            SecurityUtil::newToken(),
            SecurityUtil::newToken(),
        );
    }

}

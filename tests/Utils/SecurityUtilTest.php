<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Utils\SecurityUtil;
use Fastwf\Form\Exceptions\SecurityException;

class SecurityUtilTest extends TestCase
{

    private $algorithms;

    protected function setUp(): void
    {
        $this->algorithms = SecurityUtil::getExpectedAlgorithm();
    }

    /**
     * @covers Fastwf\Form\Utils\SecurityUtil
     */
    public function testFindBestAlgorithm()
    {
        $this->expectException(SecurityException::class);

        SecurityUtil::setExpectedAlgorithm([]);
        SecurityUtil::newToken();
    }

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

    protected function tearDown(): void
    {
        SecurityUtil::setExpectedAlgorithm($this->algorithms);
    }

}

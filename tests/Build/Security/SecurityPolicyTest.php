<?php

namespace Fastwf\Tests\Build\Security;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Build\Security\SecurityPolicy;

class SecurityPolicyTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Security\SecurityPolicy
     * @covers Fastwf\Form\Utils\SecurityUtil
     */
    public function testNewCsrfToken()
    {
        $policy = new SecurityPolicy();

        $this->assertNotNull($policy->newCsrfToken("test"));
    }

}

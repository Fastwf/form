<?php

namespace Fastwf\Tests\Build\Security;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Exceptions\ValueError;

class ASecurityPolicyTest extends TestCase
{

    /**
     * @covers Fastwf\Form\Build\Security\ASecurityPolicy
     * @covers Fastwf\Form\Utils\ArrayUtil
     */
    public function testNewCsrfTokenError()
    {
        $this->expectException(ValueError::class);

        $policy = new TestingSecurityPolicy(null);

        // Do not set input
        $policy->newCsrfToken("random_id");
    }

}

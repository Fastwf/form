<?php

namespace Fastwf\Form\Build\Security;

use Fastwf\Form\Utils\SecurityUtil;
use Fastwf\Form\Build\Security\ISecurityPolicy;

/**
 * Define the behavior of security policy to use to build secure forms.
 */
class SecurityPolicy implements ISecurityPolicy
{

    public function newCsrfToken($seed)
    {
        // Generate the CSRF token using SecurityUtil
        return SecurityUtil::newToken($seed);
    }

}

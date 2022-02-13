<?php

namespace Fastwf\Form\Build\Security;

/**
 * Define the behavior of security policy to use to build secure forms.
 */
interface ISecurityPolicy
{

    /**
     * Generate a new token for CSRF protection.
     *
     * @param string $seed the seed to use to generate the CSRF token.
     * @return string a printable string.
     */
    public function newCsrfToken($seed);

}

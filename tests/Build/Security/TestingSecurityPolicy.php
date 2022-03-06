<?php

namespace Fastwf\Tests\Build\Security;

use Fastwf\Form\Build\Security\ASecurityPolicy;

class TestingSecurityPolicy extends ASecurityPolicy
{

    private $verificationToken;
    private $lastToken;

    public function __construct($verificationToken, $name = '__token', $seed = null)
    {
        parent::__construct($name, $seed);

        $this->verificationToken = $verificationToken;
    }

    public function onSetCsrfToken($tokenId, $token)
    {
        $this->lastToken = $token;
    }

    public function getVerificationCsrfToken($tokenId)
    {
        return $this->verificationToken;
    }

    /**
     * Get the last generated token.
     *
     * @return string
     */
    public function getLastToken()
    {
        return $this->lastToken;
    }

}

<?php

namespace Fastwf\Form\Build\Security;

use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Utils\SecurityUtil;
use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Form\Build\Security\SecurityPolicyInterface;

/**
 * Define the behavior of security policy to use to build secure forms.
 */
abstract class ASecurityPolicy implements SecurityPolicyInterface
{

    /**
     * The field name for CSRF token.
     *
     * @var string
     */
    protected $name;

    /**
     * The default seed to use to generate CSRF token.
     *
     * @var string
     */
    protected $seed;

    /**
     * The form controls used for CSRF token.
     *
     * @var FormControl
     */
    protected $control = null;

    public function __construct($name = "__token", $seed = null)
    {
        $this->name = $name;
        $this->seed = $seed === null ? \random_bytes(8) : $seed;
    }

    /// IMPLEMENTATION

    /**
     * Callback called when a new CSRF token is set and set on form control.
     *
     * @param string $token the new token generated.
     * @return void
     */
    protected abstract function onSetCsrfToken($tokenId, $token);

    public function getFieldName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     * 
     * @throws ValueError when no form control is attached to this security policy.
     */
    public function newCsrfToken($tokenId)
    {
        if ($this->control === null)
        {
            throw new ValueError("The form control is null, call setControl() method before");
        }

        // Generate the CSRF token using SecurityUtil
        $token = SecurityUtil::newToken($this->seed);

        $this->control->setValue($token);

        // Call callback to perform any action with the generated token
        $this->onSetCsrfToken($tokenId, $token);
    }

    public function setControl($control)
    {
        $this->control = $control;
    }

}

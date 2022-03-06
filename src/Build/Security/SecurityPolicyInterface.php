<?php

namespace Fastwf\Form\Build\Security;

/**
 * Define the behavior of security policy to use to build secure forms.
 */
interface SecurityPolicyInterface
{

    /**
     * Get the CSRF token to use for verification.
     * 
     * When the token field is generated it's constraint use the value returned by this method.
     *
     * @param string $tokenId A token id that is unique across page forms.
     * @return string the CSRF token.
     */
    public function getVerificationCsrfToken($tokenId);

    /**
     * Get the field name for the CSRF token form control.
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Generate a new token for CSRF protection and set the value on attached form.
     *
     * @param string $tokenId A token id that is unique across page forms.
     * @return void
     */
    public function newCsrfToken($tokenId);

    /**
     * Set the form control that hold the CSRF token.
     *
     * @param FormControl $control the form control to update when newCsrfToken is called.
     * @return void
     */
    public function setControl($control);

}

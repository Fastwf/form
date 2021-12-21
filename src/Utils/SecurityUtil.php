<?php

namespace Fastwf\Form\Utils;

/**
 * Security utility class.
 */
class SecurityUtil
{

    /**
     * Generate a token for CSRF or XSRF protection.
     *
     * @return string a base64 encoded random token.
     */
    public static function newToken()
    {
        return base64_encode(
            random_bytes(64)
        );
    }

}

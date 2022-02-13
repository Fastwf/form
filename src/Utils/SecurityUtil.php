<?php

namespace Fastwf\Form\Utils;

use Fastwf\Form\Exceptions\SecurityException;

/**
 * Security utility class.
 */
class SecurityUtil
{

    /**
     * The list of cryptographic algorithms to use from the prefered to the less.
     *
     * @var array
     */
    private static $EXPECTED_ALGORITHMS = ['sha256', "sha1", "md5"];

    /**
     * The crypto algorithm available to generate tokens.
     *
     * @var string|null
     */
    private static $ALGORITHM = null;

    /**
     * Try to find the best algorithm according to algorithms available on the device.
     *
     * @return string the prefered and available cryptographic algorithm.
     * @throws SecurityException when no cryptographic algorithm is found.
     */
    private static function findBestAlgorithm()
    {
        $algorithms = \hash_algos();

        foreach (self::$EXPECTED_ALGORITHMS as $algo) {
            if (\in_array($algo, $algorithms))
            {
                return $algo;
            }
        }

        throw new SecurityException("No cryptographic algorithm found.");
    }

    /**
     * Set the new list of algorithm to use to use to find a crptographic algorithm.
     *
     * @param array $algorithms the list of algorithms to use to find the best algorithm.
     * @return void
     */
    public static function setExpectedAlgorithm($algorithms)
    {
        self::$EXPECTED_ALGORITHMS = $algorithms;

        self::$ALGORITHM = null;
    }

    /**
     * Get the current list of expected algorithm.
     *
     * @return array the list of crptographic algorithms.
     */
    public static function getExpectedAlgorithm()
    {
        return self::$EXPECTED_ALGORITHMS;
    }

    /**
     * Generate a token for CSRF or XSRF protection.
     *
     * @param string $seed a specific seed to use with random system to create a token
     * @return string a base64 encoded random token.
     */
    public static function newToken($seed = "")
    {
        if (self::$ALGORITHM === null)
        {
            // Find the best algorithm available
            self::$ALGORITHM = self::findBestAlgorithm();
        }

        return base64_encode(
            \hash(self::$ALGORITHM, $seed . random_bytes(64), true),
        );
    }

}

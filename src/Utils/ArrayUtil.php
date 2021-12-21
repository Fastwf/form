<?php

namespace Fastwf\Form\Utils;

use Fastwf\Form\Exceptions\KeyError;

/**
 * Utility class that allows to handle array.
 */
class ArrayUtil
{

    /**
     * Get the value associated to the key in the array oe return the default value.
     *
     * @param array $array the array where the property is stored.
     * @param string $key the property name.
     * @param mixed|null $default the default value to use
     * @return mixed|null the value associated in array or $default.
     */
    public static function &getSafe(&$array, $key, $default = null)
    {
        if (\array_key_exists($key, $array))
        {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Get the value associated to key or throw exception.
     *
     * @param array $array the array where the property is stored.
     * @param string $key the property name.
     * @return mixed the value associated in array.
     * @throws KeyError the exception thrown when the key is missing.
     */
    public static function &get(&$array, $key)
    {
        if (\array_key_exists($key, $array))
        {
            return $array[$key];
        }
        else
        {
            throw new KeyError("The '$key' is required");
        }
    }

}

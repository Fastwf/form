<?php

namespace Fastwf\Form\Utils;

/**
 * Utils that gather code required by multiple Entities.
 */
class EntityUtil
{
    
    /**
     * Update the HTML attributes to set the 'multiple' value.
     *
     * @param boolean $multiple true to set multiple flag
     * @param array $attributes (out) the non null array of HTML attributes of the entity. 
     * @return void
     */
    public static function synchronizeMultiple($multiple, &$attributes)
    {
        if ($multiple)
        {
            $attributes['multiple'] = true;
        }
        else if (\array_key_exists('multiple', $attributes))
        {
            // multiple === false => remove the multiple attribute
            unset($attributes['multiple']);
        }
    }

}

<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * This constraint validate null and string values. When null is provided the value is converted as empty string.
 */
class StringField implements Constraint {

    public function validate($node, $context)
    {
        $value = $node->get();

        $violation = null;
        if ($value === null)
        {
            // Convert the value as empty string
            $node->set(['value' => '']);
        }
        else if (\gettype($value) !== 'string')
        {
            $violation = $context->violation($value, 'field-string', []);
        }

        return $violation;
    }

}

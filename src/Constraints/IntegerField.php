<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * This constraint allows to validate string integer values and convert the value node as integer.
 */
class IntegerField implements Constraint {

    public function validate($node, $context)
    {
        $value = $node->get();

        if (\ctype_digit($value))
        {
            $violation = null;

            // Convert the value as integer
            $node->set(['value' => (int) $value]);
        }
        else
        {
            $violation = $context->violation($value, 'field-integer', []);
        }

        return $violation;
    }

}

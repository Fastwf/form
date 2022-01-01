<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * This constraint allows to validate string boolean representation and convert the value node as boolean.
 */
class BooleanField implements Constraint {

    public function validate($node, $context)
    {
        $value = $node->get();

        $violation = null;
        if (\in_array($value, ['true', 'on']))
        {
            // Set value to true
            $node->set(['value' => true]);
        }
        else if (\in_array($value, ['off', 'false', null]))
        {
            // Set value to false
            $node->set(['value' => false]);
        }
        else
        {
            $violation = $context->violation($value, 'field-double', []);
        }

        return $violation;
    }

}
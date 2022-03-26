<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * This constraint allows to validate string boolean representation and convert the value node as boolean.
 */
class BooleanField implements Constraint {

    /**
     * Constant that describe positive values.
     */
    public const POSITIVE_VALUES = ['true', 'on'];

    public function validate($node, $context)
    {
        $value = $node->get();

        $violation = null;
        if (is_bool($value))
        {
            $node->set(['value' => $value]);
        }
        else if (\in_array($value, self::POSITIVE_VALUES))
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
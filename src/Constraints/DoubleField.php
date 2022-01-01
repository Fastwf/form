<?php

namespace Fastwf\Form\Constraints;

use Fastwf\Constraint\Api\Constraint;

/**
 * This constraint allows to validate string double values and convert the value node as double.
 */
class DoubleField implements Constraint {

    public function validate($node, $context)
    {
        $value = $node->get();

        if (\preg_match('/^\\d+(\\.\\d*)?$/', $value) === 1)
        {
            $violation = null;

            // Convert the value as double
            $node->set(['value' => (double) $value]);
        }
        else
        {
            $violation = $context->violation($value, 'field-double', []);
        }

        return $violation;
    }

}

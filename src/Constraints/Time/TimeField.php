<?php

namespace Fastwf\Form\Constraints\Time;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Utils\DateIntervalUtil;

/**
 * Constraint validator for time fields.
 */
class TimeField implements Constraint
{

    public function validate($node, $context)
    {
        $value = $node->get();
        
        $time = $value instanceof \DateInterval
            ? $value
            : DateIntervalUtil::getTime($value);

        if ($time === null)
        {
            $violation = $context->violation($value, 'time-field', []);
        }
        else
        {
            $violation = null;
            // Convert the value
            $node->set(['value' => $time]);
        }

        return $violation;
    }

}

<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Constraint\Api\Constraint;

/**
 * Constraint validator that transform string week sequence to datetime.
 */
class WeekField implements Constraint
{

    public function validate($node, $context)
    {
        $value = $node->get();
        
        $date = $value instanceof \DateTime
            ? $value
            : DateTimeUtil::getWeek($value);
        
        if ($date === null)
        {
            $violation = $context->violation($value, 'week-field', []);
        }
        else
        {
            $violation = null;
            // Convert the value
            $node->set(['value' => $date]);
        }

        return $violation;
    }

}

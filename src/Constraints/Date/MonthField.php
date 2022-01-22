<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Form\Utils\DateTimeUtil;
use Fastwf\Constraint\Api\Constraint;

/**
 * Constraint validator that transform string month sequence to datetime.
 */
class MonthField implements Constraint
{

    public function validate($node, $context)
    {
        $value = $node->get();
        
        $date = DateTimeUtil::getMonth($value);
        
        if ($date === null)
        {
            $violation = $context->violation($value, 'month-field', []);
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

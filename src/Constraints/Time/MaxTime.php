<?php

namespace Fastwf\Form\Constraints\Time;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Utils\DateIntervalUtil;

/**
 * Max time constraint validator.
 */
class MaxTime implements Constraint
{

    private $interval;

    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    public function validate($node, $context)
    {
        // The value is considered as DateInterval object.
        $value = $node->get();
        
        return DateIntervalUtil::toSeconds($value) - DateIntervalUtil::toSeconds($this->interval) > 0
            ?  $context->violation($value, 'max-time', ['time' => $this->interval])
            : null;
    }

}

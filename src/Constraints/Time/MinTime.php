<?php

namespace Fastwf\Form\Constraints\Time;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Utils\DateIntervalUtil;

/**
 * Max time constraint validator.
 */
class MinTime implements Constraint
{

    private $interval;

    /**
     * Constructor.
     *
     * @param \DateInterval|null $interval the minimum as date interval or null to use a date interval of 0s
     */
    public function __construct($interval = null)
    {
        if ($interval === null)
        {
            $this->interval = new \DateInterval("P0D");
        }
        else
        {
            $this->interval = $interval;
        }
    }

    public function validate($node, $context)
    {
        // The value is considered as DateInterval object.
        $value = $node->get();

        return DateIntervalUtil::toSeconds($value) - DateIntervalUtil::toSeconds($this->interval) < 0
            ?  $context->violation($value, 'min-time', ['time' => $this->interval])
            : null;
    }

}

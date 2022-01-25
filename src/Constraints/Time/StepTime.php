<?php

namespace Fastwf\Form\Constraints\Time;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Utils\DateIntervalUtil;

/**
 * Allows to validate the time using step in seconds.
 */
class StepTime implements Constraint
{

    /**
     * The step to verify.
     *
     * @var double
     */
    protected $step;

    /**
     * The offset to use to validate the step.
     *
     * @var double
     */
    protected $from;

    /**
     * Constructor
     *
     * @param int|double $step the step in seconds 
     * @param \DateInterval $from
     */
    public function __construct($step, $from = null)
    {
        if ($step === 0)
        {
            throw new ValueError("0 is not allowed");
        }
        else
        {
            $this->step = $step;
        }

        if ($from === null)
        {
            // By default the start is 0s
            $this->from = new \DateInterval("P0D");
        }
        else
        {
            $this->from = $from;
        }
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        $quotient = (DateIntervalUtil::toSeconds($value) - DateIntervalUtil::toSeconds($this->from)) / $this->step;

        // Modulus cannot be used for double divider
        return ((int) $quotient) == $quotient
            ? null
            : $context->violation($value, 'step-time', ['step' => $this->step]);
    }

}

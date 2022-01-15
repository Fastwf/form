<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Exceptions\ValueError;

class StepDateTime implements Constraint
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
     * @param \DateTime $from
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
            // By default the starting date is 1970-01-01
            $this->from = new \DateTime('1970-01-01 00:00:00');
        }
        else
        {
            $this->from = $from;
        }
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        $quotient = ($value->getTimestamp() - $this->from->getTimestamp()) / $this->step;

        // Modulus cannot be used for double divider
        return ((int) $quotient) == $quotient
            ? null
            : $context->violation($value, 'step-datetime', ['step' => $this->step]);

        return $violation;
    }

}
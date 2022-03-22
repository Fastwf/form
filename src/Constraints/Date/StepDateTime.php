<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Api\Exceptions\ValueError;

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
     * @var \DateTime
     */
    protected $from;

    /**
     * The name of the violation to provide when validation failed.
     *
     * @var string
     */
    private $violationCode;

    /**
     * Constructor.
     *
     * @param int|double $step The step in seconds.
     * @param \DateTime $from The start time to use to validate the step.
     */
    public function __construct($step, $from = null, $violationCode = "step-datetime")
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

        $this->violationCode = $violationCode;
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        $quotient = ($value->getTimestamp() - $this->from->getTimestamp()) / $this->step;

        // Modulus cannot be used for double divider
        //  Use ceil to obtain the integer value
        //  Convert the values to string to have better representation of double values
        return ((string) \ceil($quotient)) == ((string) $quotient)
            ? null
            : $context->violation($value, $this->violationCode, ['step' => $this->step]);
    }

}

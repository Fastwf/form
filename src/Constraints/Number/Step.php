<?php

namespace Fastwf\Form\Constraints\Number;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Exceptions\ValueError;

class Step implements Constraint
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

    public function __construct($step, $from = 0)
    {
        if ($step === 0)
        {
            throw new ValueError("0 is not allowed");
        }
        else
        {
            $this->step = $step;
        }

        $this->from = $from;
    }

    public function validate($node, $context)
    {
        $value = $node->get();

        $quotient = ($value - $this->from) / $this->step;

        // Modulus cannot be used for double divider
        return ((int) $quotient) == $quotient
            ? null
            : $context->violation($value, 'step', ['step' => $this->step]);

        return $violation;
    }

}

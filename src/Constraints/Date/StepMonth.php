<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;
use Fastwf\Api\Exceptions\ValueError;

/**
 * Constraint that verify the step based on mount number.
 * 
 * As mentionned in the mozilla developper documentation (https://developer.mozilla.org/fr/docs/Web/HTML/Element/Input/month) the "step"
 * attribute is not correctly handled by navigators.  
 * So this constraint validate the month step by respecting the next rules:
 * - step="1" correspond to 1 month
 * - when min attribute is not provided, the "from" month used is "1970-01"
 * 
 * So with step="2" and min is not provided:
 * - "2022-01" is valid
 * - "2020-06" is invalid
 * with step="2" and min="2000-02":
 * - "2022-01" is invalid
 * - "2020-06" is valid
 */
class StepMonth implements Constraint
{

    /**
     * The step to verify.
     *
     * @var int
     */
    protected $step;

    /**
     * The offset to use to validate the step.
     *
     * @var \DateTime
     */
    protected $from;

    /**
     * Constructor.
     *
     * @param int $step The step in months. 
     * @param \DateTime $from The start time to use to validate the step.
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

        $year = (int) $value->format("Y");
        $month = (int) $value->format("n");

        $fromYear = (int) $this->from->format("Y");
        $fromMonth = (int) $this->from->format("n");

        return (12 * ($year - $fromYear) + $month - $fromMonth) % $this->step === 0
            ? null
            : $context->violation($value, 'step-month', ['step' => $this->step]);
    }

}

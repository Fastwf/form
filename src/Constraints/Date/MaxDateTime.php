<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;

class MaxDateTime implements Constraint
{

    private $dateTime;

    public function __construct($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function validate($node, $context)
    {
        // The value is considered as DateTime object.
        $value = $node->get();
        
        return $value > $this->dateTime
            ?  $context->violation($value, 'max-datetime', ['dateTime' => $this->dateTime])
            : null;
    }

}
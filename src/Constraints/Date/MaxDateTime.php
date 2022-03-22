<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;

class MaxDateTime implements Constraint
{

    /**
     * An identifier to know what kind of date is evaluated (date, date time, week, month).
     *
     * @var string
     */
    private $type;

    /**
     * The limit to use for validation.
     *
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Constructor.
     *
     * @param \DateTime $dateTime the limit datetime.
     * @param string $type the kind of datetime evaluated (date, date time, week, month).
     */
    public function __construct($dateTime, $type)
    {
        $this->dateTime = $dateTime;
        $this->type = $type;
    }

    public function validate($node, $context)
    {
        // The value is considered as DateTime object.
        $value = $node->get();
        
        return $value > $this->dateTime
            ?  $context->violation($value, 'max-datetime', ['dateTime' => $this->dateTime, 'type' => $this->type])
            : null;
    }

}

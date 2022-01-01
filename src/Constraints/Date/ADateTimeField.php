<?php

namespace Fastwf\Form\Constraints\Date;

use Fastwf\Constraint\Api\Constraint;

/**
 * Base class to use to validate and convert values from string to date or datetime.
 */
abstract class ADateTimeField implements Constraint
{

    protected $format;

    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * Get the field constraint string unique id.
     */
    protected abstract function getName();

    /**
     * Try to parse the value as DateTime according to the format.
     *
     * @param string $value the value to parse
     * @return \DateTime|null the value parsed or null when failed
     */
    protected abstract function parse($value);

    public function validate($node, $context)
    {
        $value = $node->get();
        
        $date = $this->parse($value);
        
        if ($date === null)
        {
            $violation = $context->violation($value, $this->getName(), []);
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

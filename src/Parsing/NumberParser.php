<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Parsing\AParser;
use Fastwf\Form\Utils\ArrayUtil;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Number parser for integer or double values.
 */
class NumberParser extends AParser
{

    /**
     * The default value of the step in number or range input.
     * 
     * @var string 
     */
    private const STEP_DEFAULT = "1";

    /**
     * The patern corresponding to an integer or double string representation.
     * 
     * @var string
     */
    private const NUMBER_PATTERN = "/^(\\d+)(?:(\\.\\d+))?$/";

    protected function valToStr($value, $_)
    {
        // Use the default integer/double convertion to string
        return (string) $value;
    }

    protected function strToVal($sequence, $control)
    {
        // Convert the value according to the sequence and the control specifications
        //
        // For number and range, step attribute allows to know if the value must be converted as double or integer
        // Control nullity of the value

        if ($sequence === null || preg_match(self::NUMBER_PATTERN, $sequence) !== 1)
        {
            // It's not a valid string number representation, so return null
            return null;
        }

        // Extract the step value from form control attributes
        $attributes = $control->getAttributes();
        if ($attributes) // attributes !== null and is not an empty array
        {
            $step = ArrayUtil::getSafe($attributes, 'step', self::STEP_DEFAULT);
        }
        else
        {
            // By default the step is set to 1
            $step = self::STEP_DEFAULT;
        }

        // Evaluate if the field is an integer value or a double
        $matches = [];
        if (\preg_match(self::NUMBER_PATTERN, (string) $step, $matches) === 1)
        {
            // When the array have 2 elements, the floating point party is empty so it's an integer and not a double
            $isInteger = \count($matches) === 2;
        }
        else {
            $isInteger = true;
        }

        return $isInteger ? (int) $sequence : (double) $sequence;
    }

}

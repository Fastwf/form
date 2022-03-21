<?php

namespace Fastwf\Form\Parsing;

use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Parsing\ParserInterface;

/**
 * Input radio data parser.
 * 
 * @inherits AParser<string|boolean>
 */
class CheckboxParser implements ParserInterface
{

    public function strigify($value, $control)
    {
        /** @var Checkbox $control */
        return $control->getValueAttribute();
    }

    /**
     * Return the data corresponding to the sequence and the control state.
     *
     * When the value attribute is 'on' or 'true' the data is true/false
     * Otherwise the data is the value attribute or null
     * 
     * @return boolean|string|null the value of the checkbox
     */
    public function parse($sequence, $control)
    {
        /** @var Checkbox $control */
        if (\in_array($control->getValueAttribute(), ['true', 'on']))
        {
            // It's a boolean checkbox
            return $control->isChecked();
        }
        else
        {
            // The value is a string to set when it's checked
            return $control->isChecked() ? $control->getValueAttribute() : null;
        }
    }

}

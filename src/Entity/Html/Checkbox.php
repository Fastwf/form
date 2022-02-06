<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\CheckableInput;

/**
 * The checkbox input allows to handle correctly values.
 */
class Checkbox extends CheckableInput
{

    public function __construct($parameters = [])
    {
        parent::__construct(\array_merge($parameters, ['type' => 'checkbox']));
    }

    protected function synchronizeValue($priority)
    {
        $this->valueAttribute = ArrayUtil::getSafe($this->attributes, 'value', 'on');

        parent::synchronizeValue($priority);
    }

    /**
     * Return the data corresponding to the state of the control.
     *
     * When the value attribute is 'on' or 'true' the data is true/false
     * Otherwise the data is the value attribute or null
     * 
     * @return boolean|string|null the value of the checkbox
     */
    public function getData()
    {
        if (\in_array($this->valueAttribute, ['true', 'on']))
        {
            // It's a boolean checkbox
            return $this->checked;
        }
        else
        {
            // The value is a string to set when it's checked
            return $this->checked ? $this->valueAttribute : null;
        }
    }

}

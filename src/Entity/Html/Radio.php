<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\CheckableInput;

class Radio extends CheckableInput
{

    public function __construct($parameters = [])
    {
        parent::__construct(\array_merge($parameters, ['type' => 'radio']));
    }

    protected function synchronizeValue($priority)
    {
        $this->valueAttribute = ArrayUtil::get($this->attributes, 'value');

        if ($this->value === $this->valueAttribute)
        {
            $this->checked = true;
        }
        else
        {
            // When the value not match the value attribute the value of the control is invalid
            //  -> Uncheck and reset value to null
            $this->checked = false;
            $this->value = null;
        }
    }

    public function setChecked($checked)
    {
        parent::setChecked($checked);

        $this->value = $this->valueAttribute;
    }

    public function getData()
    {
        return $this->checked ? $this->valueAttribute : null;
    }

}

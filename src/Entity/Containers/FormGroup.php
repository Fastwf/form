<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Containers\FormContainer;

class FormGroup extends FormContainer
{

    public function setValue($value)
    {
        foreach ($this->controls as $control) {
            $name = $control->getName();

            if (\array_key_exists($name, $value))
            {
                $control->setValue($value[$name]);
            }
        }
    }

    public function getContainerType()
    {
        return 'object';
    }

}

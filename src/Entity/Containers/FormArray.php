<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Containers\FormContainer;

class FormArray extends FormContainer
{
    
    public function setValue($value)
    {
        $length = \min(
            \count($this->controls),
            \count($value),
        );
        for ($index = 0; $index < $length; $index++)
        {
            $this->controls[$index] = $value[$index];
        }
    }

    public function getContainerType()
    {
        return 'array';
    }

}

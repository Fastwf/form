<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Entity\FormControl;

/**
 * Entity definition for "textarea" html element.
 */
class Textarea extends FormControl
{

    public function getTag()
    {
        return 'textarea';
    }

    /**
     * Return always the value as string.
     *
     * @return string
     */
    public function getData()
    {
        return $this->value === null ? "" : $this->value;
    }

}

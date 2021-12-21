<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Entity\FormControl;

class Textarea extends FormControl
{

    public function getTag()
    {
        return 'textarea';
    }

}

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

        parent::synchronizeValue($priority);
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

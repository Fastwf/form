<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Parsing\RadioParser;
use Fastwf\Form\Entity\Html\CheckableInput;

/**
 * Entity definition for "input[radio]" html element.
 */
class Radio extends CheckableInput
{

    public function __construct($parameters = [])
    {
        parent::__construct(\array_merge($parameters, ['type' => 'radio']));
    }

    protected function getDefaultParser($parameters)
    {
        return new RadioParser();
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

}

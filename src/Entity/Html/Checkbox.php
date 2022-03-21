<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Entity\Containers\CheckboxGroup;
use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\CheckableInput;
use Fastwf\Form\Parsing\CheckboxParser;

/**
 * The checkbox input allows to handle correctly values.
 */
class Checkbox extends CheckableInput
{

    public function __construct($parameters = [])
    {
        parent::__construct(\array_merge($parameters, ['type' => 'checkbox']));
    }

    protected function getDefaultParser($parameters)
    {
        return new CheckboxParser();
    }

    protected function synchronizeValue($priority)
    {
        $this->valueAttribute = ArrayUtil::getSafe($this->attributes, 'value', 'on');

        parent::synchronizeValue($priority);
    }

    public function getFullName()
    {
        $name = parent::getFullName();

        if ($this->parent instanceof CheckboxGroup)
        {
            // The checkbox can be used with checkbox group for multiple selection alternative.
            // In that case checkboxes of this group must have the '[]' suffix to allows to collect multiple data using php builtin body
            // parser.
            $name .= '[]';
        }

        return $name;
    }

}

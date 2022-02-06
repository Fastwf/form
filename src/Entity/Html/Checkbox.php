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

        switch ($priority)
        {
            case self::SYNC_VALUE:
                // Set checked when value == attribute value
                $this->checked = $this->value === $this->valueAttribute;
                break;
            case self::SYNC_CHECKED:
                // Set the value as attribute value when it is checked
                $this->value = $this->checked ? $this->valueAttribute : null;
                break;
            case self::SYNC_CONSTRUCT:
            case self::SYNC_ATTRIBUTES:
            default:
                // The content of the value have the priority on checked attribute, check state depend on value equality.
                //  When the value is null, the strategy is based on checked attribute,
                //  else the value is reset to null and checked status to false
                if ($this->value === $this->valueAttribute)
                {
                    $this->checked = true;
                }
                else if ($this->value === null && $this->checked)
                {
                    $this->value = $this->valueAttribute;
                }
                else
                {
                    $this->value = null;
                    $this->checked = false;
                }
                break;
        }
    }

    public function setChecked($checked)
    {
        parent::setChecked($checked);

        $this->value = $this->valueAttribute;
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

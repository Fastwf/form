<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Utils\EntityUtil;
use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Entity\Options\OptionGroup;

/**
 * Entity definition for "select" html element.
 */
class Select extends FormControl
{

    /**
     * The list of values selected.
     *
     * @var array
     */
    protected $value;

    /**
     * Allows to know if the select can receive multiple values.
     *
     * @var boolean
     */
    protected $multiple;

    /**
     * The list of abstract option implementations (options <and>or option groups).
     *
     * @var array
     */
    protected $options;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->multiple = ArrayUtil::getSafe($parameters, 'multiple', false);
        $this->options = ArrayUtil::get($parameters, 'options');

        $this->setValue(ArrayUtil::getSafe($parameters, 'value', []));
        EntityUtil::synchronizeMultiple($this->multiple, $this->attributes);
    }

    public function getFullName()
    {
        $name = parent::getFullName();

        if ($this->multiple)
        {
            // When is set to multiple it's required to set the name as dynamic array
            $name .= '[]';
        }

        return $name;
    }

    /**
     * Update the option selection flag according to the select value.
     *
     * @return void
     */
    private function updateSelection()
    {
        $length = \count($this->options);

        $optionsCopy = $this->options;
        $index = 0;
        while ($index < $length)
        {
            $option = $optionsCopy[$index];

            if ($option instanceof OptionGroup)
            {
                // Insert child options in the array
                $childOptions = $option->getOptions();
                $length += (\count($childOptions) - 1);

                \array_splice($optionsCopy, $index, 1, $childOptions);
            }
            else
            {
                // This is an instance of Option -> update selection
                $option->setSelected(\in_array($option->getValue(), $this->value));
                $index++;
            }
        }
    }

    public function setValue($value)
    {
        if ($value === null)
        {
            $this->value = [];
        }
        else if (\gettype($value) === 'array')
        {
            $this->value = $value;
        }
        else
        {
            $this->value = [$value];
        }

        $this->updateSelection();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        EntityUtil::synchronizeMultiple($this->multiple, $this->attributes);
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        $this->updateSelection();
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getTag()
    {
        return 'select';
    }

    /**
     * For select with multiple flag, the value returned is an array of choices.
     * For single choice, the value is the choice as string or null when the selection is empty.
     * 
     * @return array|string|null
     */
    public function getData()
    {
        if  ($this->multiple)
        {
            $data = $this->value;
        }
        else if (empty($this->value)) {
            $data = null;
        } 
        else
        {
            $data = $this->value[0];
        }

        return $data;
    }

}

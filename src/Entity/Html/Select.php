<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Entity\Options\OptionGroup;

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

    public function getName()
    {
        // For multiple mode, the name must be an array
        $name = $this->name;
        if ($this->multiple)
        {
            if (\preg_match('/^.+\\[\\]$/', $name) !== 1)
            {
                $name .= '[]';
            }
        }
        
        return $name;
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

<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

/**
 * Entity definition for "input[file]" html element.
 */
class InputFile extends Input
{

    /**
     * A flag that indicate the input file to have multiple attribute.
     *
     * @var boolean
     */
    protected $multiple;

    public function __construct($parameters)
    {
        parent::__construct(\array_merge($parameters, ['type' => 'file']));

        $this->multiple = ArrayUtil::getSafe($parameters, 'multiple', false);

        $this->synchronizeMultiple();
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
     * Update the HTML attributes to set the 'multiple' value.
     *
     * @return void
     */
    private function synchronizeMultiple()
    {
        if ($this->multiple)
        {
            $this->attributes['multiple'] = true;
        }
        else if (\array_key_exists('multiple', $this->attributes))
        {
            // multiple === false => remove the multiple attribute
            unset($this->attributes['multiple']);
        }
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        $this->synchronizeMultiple();
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

}

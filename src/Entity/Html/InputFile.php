<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

/**
 * Entity for input[file].
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

    public function getMultiple()
    {
        return $this->multiple;
    }

}

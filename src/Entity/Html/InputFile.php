<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Utils\EntityUtil;
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

    /**
     * {@inheritDoc}
     * 
     * @param array{multiple?:boolean} $parameters The input file parameters that extends {@see Input::__construct} parameters.
     */
    public function __construct($parameters)
    {
        parent::__construct(\array_merge($parameters, ['type' => 'file']));

        $this->multiple = ArrayUtil::getSafe($parameters, 'multiple', false);

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

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        EntityUtil::synchronizeMultiple($this->multiple, $this->attributes);
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

}

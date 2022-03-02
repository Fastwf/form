<?php

namespace Fastwf\Form\Entity\Options;

use Fastwf\Form\Entity\Element;
use Fastwf\Form\Utils\ArrayUtil;

/**
 * Abstract class that define common base of option and option group classes.
 */
abstract class AOption implements Element
{

    /**
     * The section name.
     *
     * @var string
     */
    protected $label;

    /**
     * Allows to disable or not the form control.
     *
     * @var boolean
     */
    protected $disabled;

    /**
     * Constructor.
     *
     * @param array{label?:string,disabled?:boolean} $parameters The base option/option group parameters.
     */
    public function __construct($parameters = [])
    {
        $this->label = (string) ArrayUtil::getSafe($parameters, 'label', null);
        $this->disabled = ArrayUtil::getSafe($parameters, 'disabled', false);
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }
    
}

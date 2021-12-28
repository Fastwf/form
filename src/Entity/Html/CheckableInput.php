<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

/**
 * Base class for checkbox and radio inputs.
 */
abstract class CheckableInput extends Input
{

    /**
     * True when the input must be checked.
     *
     * @var boolean
     */
    protected $checked;

    /**
     * This value is the value to associate to the checkbox.
     * 
     * ...input value="$valueAttribute"...
     *
     * @var string
     */
    protected $valueAttribute;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->synchronizeValue();
    }

    public function setAttributes($attributes)
    {
        parent::setAttributes($attributes);

        $this->synchronizeValue();
    }

    public function setValue($value)
    {
        parent::setValue($value);

        $this->synchronizeValue();
    }

    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * Synchronise internal state to match the real state (correct checked and value properties).
     *
     * @return void
     */
    protected abstract function synchronizeValue();

}

<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;

abstract class FormContainer extends Control
{

    /**
     * The array of controls.
     *
     * @var array
     */
    protected $controls;
    
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->controls = ArrayUtil::get($parameters, 'controls');
    }

    public function setControls($controls)
    {
        $this->controls = $controls;
    }

    public function getControls()
    {
        return $this->controls;
    }

    /**
     * Add a new control node at the end of the container.
     *
     * @param Control $control
     * @return void
     */
    public function addControl($control)
    {
        \array_push($this->controls, $control);
    }

    public function setControlAt($index, $control)
    {
        $this->controls[$index] = $control;
    }

    public function getControlAt($index)
    {
        return ArrayUtil::get($this->controls, $index);
    }

    /**
     * Search the control associated to the $name.
     *
     * @param string $name the name of the control node
     * @return Control|null the first node matching or null 
     */
    public function findControl($name)
    {
        // Search in the control array while the name is not found
        foreach ($this->controls as $control) {
            if ($control->getName() === $name)
            {
                return $control;
            }
        }

        return null;
    }

    public function getTag()
    {
        return null;
    }

    /**
     * Set the value on each controls of the container.
     *
     * @param mixed $value
     * @return void
     */
    public abstract function setValue($value);

    /**
     * Allows to identity the container type of inputs.
     * 
     * Warning: this method makes sense only when getTag() return null.
     *
     * @return string the container name (array or object)
     */
    public abstract function getContainerType();

}

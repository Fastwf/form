<?php

namespace Fastwf\Form\Entity\Containers;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Containers\Container;

/**
 * Base class for group of multiple different controls.
 */
abstract class AFormGroup extends Control implements Container
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

    /// IMPLEMENT METHODS

    public function getTag()
    {
        return null;
    }

    /// PUBLIC METHODS

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

    /**
     * Set the control at the given position.
     *
     * @param integer $index the index where the control must be inserted (warning: no control on index).
     * @param Control $control the control implementation object.
     * @return void
     */
    public function setControlAt($index, $control)
    {
        $this->controls[$index] = $control;
    }

    /**
     * Try to find the control at the given index.
     *
     * @param integer $index the index of the control.
     * @return Control the control found at index.
     * @throws KeyError when the index not exists.
     */
    public function getControlAt($index)
    {
        return ArrayUtil::get($this->controls, $index);
    }

}

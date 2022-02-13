<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Build\ContainerBuilder;
use Fastwf\Form\Build\Groups\ArrayBuilder;
use Fastwf\Form\Build\Groups\GroupBuilder;
use Fastwf\Form\Build\Groups\IArrayBuilder;
use Fastwf\Form\Build\ContainerGroupBuilder;
use Fastwf\Form\Entity\Containers\FormArray;

/**
 * The builder able to generate FormArray containers.
 */
class ArrayBuilder extends ContainerBuilder implements IArrayBuilder
{

    /**
     * The control that the FormArray must use.
     *
     * @var Control
     */
    protected $control;

    /**
     * The parent builder.
     *
     * @var ContainerBuilder
     */
    protected $builder;

    /**
     * The callback to call when this builder call the "buildInParent" method.
     *
     * @var callable
     */
    protected $onBuildCallback;
    
    public function __construct($name, $builder, $onBuildCallback = null, $constraintBuilder = null)
    {
        parent::__construct($name, $constraintBuilder);

        $this->builder = $builder;
        $this->onBuildCallback = $onBuildCallback;
    }

    /// IMPLEMENTATION

    public function ofInput($type = 'text', $options = [])
    {
        $this->control = $this->newInput(null, $type, $options);

        return $this;
    }

    public function ofTextarea($options)
    {
        $this->control = $this->newTextarea(null, $options);

        return $this;
    }

    public function ofSelect($options)
    {
        $this->control = $this->newSelect(null, $options);

        return $this;
    }

    public function ofCheckbox($options)
    {
        $this->control = $this->newCheckbox(null, $options);

        return $this;
    }

    public function ofCheckboxGroup($options)
    {
        $this->control = $this->newCheckboxGroup(null, $options);

        return $this;
    }

    public function ofRadioGroup($options)
    {
        $this->control = $this->newRadioGroup(null, $options);

        return $this;
    }

    public function ofButton($type = Button::TYPE_SUBMIT, $options = [])
    {
        $this->control = $this->newButton($type, $options);

        return $this;
    }

    public function ofGroup()
    {
        $groupControl = &$this->control;

        return new GroupBuilder(
            null,
            $this,
            function ($formGroup) use (&$groupControl) {
                // Set the control as $formGroup value using its reference
                $groupControl = $formGroup;
            },
            $this->constraintBuilder,
        );
    }

    public function ofArray()
    {
        $arrayControl = &$this->control;

        return new ArrayBuilder(
            null,
            $this,
            function ($formGroup) use (&$arrayControl) {
                // Set the control as $formGroup value using its reference
                $arrayControl = $formGroup;
            },
            $this->constraintBuilder,
        );
    }

    /**
     * Generate the FormGroup from the builder specifications.
     *
     * @return FormArray
     */
    public function build()
    {
        $this->parameters['control'] = $this->control;

        return new FormArray($this->parameters);
    }
    
    /// PUBLIC METHODS

    /**
     * Build the FormArray from current specifications, add the form array build then return the parent builder.
     *
     * @return FormBuilder|GroupBuilder|ArrayBuilder the parent group builder.
     */
    public function buildInParent()
    {
        $formGroup = $this->build();

        if (\is_callable($this->onBuildCallback))
        {
            \call_user_func($this->onBuildCallback, $formGroup);
        }

        return $this->builder;
    }

}

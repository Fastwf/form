<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Build\ContainerBuilder;
use Fastwf\Form\Build\Groups\GroupBuilder;
use Fastwf\Form\Build\Groups\IArrayBuilder;
use Fastwf\Form\Entity\Containers\FormArray;
use Fastwf\Form\Utils\ArrayUtil;

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
     * @var Callable<FormArray>|null
     */
    protected $onBuildCallback;
    
    /**
     * {@inheritDoc}
     * 
     * $options:
     * - `min_size` (default 1): The minimum number of items in the collection.
     * - `defaultValue` (default null): The default value to apply on each control of the array collection.
     * - `onBuildCallback` (default null): The callback to call when the group is built.
     * 
     * {@see AGroupBuilder::__construct} for details about other options. 
     *
     * @param string|null $name the group name.
     * @param ContainerBuilder $builder the parent builder.
     * @param array{
     *      constraintBuilder?:ConstraintBuilder,
     *      attributes:array,
     *      label?:string,
     *      help?:string,
     *      min_size?:number,
     *      defaultValue?:array<mixed>,
     *      onBuildCallback?:Callable<FormArray>
     * } $options The builder parameters.
     */
    public function __construct($name, $builder, $options = [])
    {
        parent::__construct($name, $options);

        $this->builder = $builder;
        $this->onBuildCallback = ArrayUtil::getSafe($options, 'onBuildCallback');

        // Add more parameters to the array group
        ArrayUtil::merge($options, $this->parameters, ['min_size']);
        if (\array_key_exists('defaultValue', $options)) {
            $this->parameters['value'] = $options['defaultValue'];
        }
    }

    /// IMPLEMENTATION

    public function ofInput($type = 'text', $options = [])
    {
        $this->control = $this->newInput(null, $type, $options);

        return $this;
    }

    public function ofTextarea($options = [])
    {
        $this->control = $this->newTextarea(null, $options);

        return $this;
    }

    public function ofSelect($options)
    {
        $this->control = $this->newSelect(null, $options);

        return $this;
    }

    public function ofCheckbox($options = [])
    {
        $this->control = $this->newCheckbox(null, $options);

        return $this;
    }

    public function ofInputFile($options = [])
    {
        $this->control = $this->newInputFile(null, $options);

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

    public function ofGroup($options = [])
    {
        $groupControl = &$this->control;

        $options['constraintBuilder'] = $this->constraintBuilder;
        $options['onBuildCallback'] = function ($formGroup) use (&$groupControl) {
            // Set the control as $formGroup value using its reference
            $groupControl = $formGroup;
        };

        return new GroupBuilder(null, $this, $options);
    }

    public function ofArray($options = [])
    {
        $arrayControl = &$this->control;

        $options['constraintBuilder'] = $this->constraintBuilder;
        $options['onBuildCallback'] = function ($formGroup) use (&$arrayControl) {
            // Set the control as $formGroup value using its reference
            $arrayControl = $formGroup;
        };

        // The name is required but because it will be used inside FormArray, the name will be overriden when iterate on FormArray.
        //  So use an empty name.
        return new ArrayBuilder('', $this, $options);
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

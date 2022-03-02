<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Build\ContainerBuilder;
use Fastwf\Form\Build\Groups\ArrayBuilder;
use Fastwf\Form\Build\Groups\GroupBuilder;
use Fastwf\Form\Build\Groups\IArrayBuilder;

/**
 * Basic behavior for FormGroup container builder.
 */
abstract class ContainerGroupBuilder extends ContainerBuilder
{

    /**
     * The array of controls to push in the form group.
     *
     * @var array
     */
    protected $controls = [];

    /// PUBLIC METHODS

    /**
     * Add an input form control that respect the specifications.
     * 
     * For 'radio' and 'checkbox' type use their specific method.
     * 
     * @param string $name the control name.
     * @param string $type the input type.
     * @param array $options the array of textarea options.
     * @return $this the current form builder updated.
     */
    public function addInput($name, $type = 'text', $options = [])
    {
        // Add input control
        \array_push($this->controls, $this->newInput($name, $type, $options));

        return $this;
    }

    /**
     * Add a textarea form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of textarea options.
     * @return $this the current form builder updated.
     */
    public function addTextarea($name, $options = [])
    {
        \array_push($this->controls, $this->newTextarea($name, $options));

        return $this;
    }

    /**
     * Add a select form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of select specifications.
     * @return $this the current form builder updated.
     */
    public function addSelect($name, $options)
    {
        // Add the select setup
        \array_push($this->controls, $this->newSelect($name, $options));

        return $this;
    }

    /**
     * Add input checkbox form control that respect the specifications.
     *
     * @param string $name the name of the input
     * @param array $options the array of checkbox options.
     * @return $this the current form builder updated.
     */
    public function addCheckbox($name, $options = [])
    {
        // Add checkbox control
        \array_push($this->controls, $this->newCheckbox($name, $options));

        return $this;
    }

    /**
     * Add input file form control that respect the specifications.
     *
     * @param string $name the name of the input.
     * @param array $options the array of input file options.
     * @return $this the current builder updated.
     */
    public function addInputFile($name, $options = [])
    {
        // Add checkbox control
        \array_push($this->controls, $this->newInputFile($name, $options));

        return $this;
    }

    /**
     * Add checkbox group using choices to build each input checkbox.
     *
     * @param string $name the name of the input.
     * @param array $options the array of checkbox group specifications.
     * @return $this the current builder updated.
     */
    public function addCheckboxGroup($name, $options)
    {
        // Add a checkbox group
        \array_push(
            $this->controls,
            $this->newCheckboxGroup($name, $options)
        );

        return $this;
    }

    /**
     * Add radio group using choices to build each input radio.
     *
     * @param string $name the name of the input.
     * @param array $options the array of radio group specifications.
     * @return $this the current builder updated.
     */
    public function addRadioGroup($name, $options)
    {
        // Add a radio group
        \array_push(
            $this->controls,
            $this->newRadioGroup($name, $options)
        );

        return $this;
    }

    /**
     * Add button html entity set with options.
     *
     * @param string $type the button type.
     * @param array $options the button options.
     * @return $this the current form builder updated.
     */
    public function addButton($type = Button::TYPE_SUBMIT, $options = [])
    {
        // Create a button
        \array_push($this->controls, $this->newButton($type, $options));

        return $this;
    }

    /**
     * Create a new group builder to allows to add a FormGroup.
     *
     * @param string|null $name the name of the group to add.
     * @param array $options the group option to set for FormGroup (for details {@see GroupBuilder::__construct}).
     * @return GroupBuilder
     */
    public function newGroupBuilder($name, $options = [])
    {
        $controlArray = &$this->controls;
        $index = \count($this->controls);

        $options['constraintBuilder'] = $this->constraintBuilder;
        $options['onBuildCallback'] = function ($formGroup) use ($index, &$controlArray) {
            // Insert the form group at the creation position
            \array_splice($controlArray, $index, 0, [$formGroup]);
        };

        return new GroupBuilder($name, $this, $options);
    }

    /**
     * Create a new array builder to allows to add a FormArray.
     *
     * @param string|null $name the name of the array to add.
     * @param array $options the array option to set for FormArray (for details {@see ArrayBuilder::__construct}).
     * @return IArrayBuilder a new instance of array builder.
     */
    public function newArrayBuilder($name, $options = [])
    {
        $controlArray = &$this->controls;
        $index = \count($this->controls);

        $options['constraintBuilder'] = $this->constraintBuilder;
        $options['onBuildCallback'] = function ($formArray) use ($index, &$controlArray) {
            // Insert the form group at the creation position
            \array_splice($controlArray, $index, 0, [$formArray]);
        };

        return new ArrayBuilder($name, $this, $options);
    }

}

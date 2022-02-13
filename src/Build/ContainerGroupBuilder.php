<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Entity\Html\Select;
use Fastwf\Form\Build\AGroupBuilder;
use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Entity\Html\Textarea;
use Fastwf\Form\Entity\Options\Option;
use Fastwf\Form\Build\Groups\GroupBuilder;
use Fastwf\Form\Exceptions\BuildException;
use Fastwf\Form\Entity\Options\OptionGroup;
use Fastwf\Form\Entity\Containers\FormGroup;
use Fastwf\Form\Build\Groups\RadioGroupBuilder;
use Fastwf\Form\Build\Groups\EntityGroupBuilder;
use Fastwf\Form\Build\Groups\CheckboxGroupBuilder;

/**
 * Basic behavior for FormGroup container builder.
 */
abstract class ContainerGroupBuilder extends AGroupBuilder
{

    /**
     * The array of controls to push in the form group.
     *
     * @var array
     */
    protected $controls = [];

    /**
     * The array of parameters to use to create instance of FormGroup.
     *
     * @var array
     */
    protected $parameters;

    public function __construct($name, $constraintBuilder = null)
    {
        parent::__construct($constraintBuilder);

        $this->parameters = $name === null ? [] : ['name' => $name];
    }

    /// PROTECTED METHODS

    /**
     * Generate the field option array of basic form controls.
     *
     * @param string $control the form field type ('input', 'textarea').
     * @param string|null $type when $control is 'input' set the type, else use null.
     * @param string $name the input name.
     * @param array $options the form control build options.
     * @return array the standard field options generated.
     */
    protected function createStandardOptions($control, $type, $name, $options)
    {
        // Create options
        $fieldOptions = $this->createCommonOptions($name, $options);

        // Set the default value as input value
        if (\array_key_exists('defaultValue', $options))
        {
            $fieldOptions['value'] = $options['defaultValue'];
        }

        // Create constraints
        $this->applyConstraints($fieldOptions, $options, $control, $type);

        return $fieldOptions;
    }

    /**
     * Create a new option from the specifications.
     *
     * @param array $specifications the option specification to use to generate the abstract option implementation instance. 
     * @return AOption the Option instance or OptionGroup instance.
     */
    protected function newSelectOption($specifications, $depth = 0)
    {
        // When the 'options' key is provided, we create an OptionGroup else, it is an option
        if (\array_key_exists('options', $specifications))
        {
            // Control the depth to prevent to have bad option tree
            if ($depth > 0)
            {
                throw new BuildException("OptionGroup expect only option members");
            }

            // Create group options
            $newDepth = $depth + 1;
            $options = [];
            foreach (ArrayUtil::get($specifications, 'options') as $optSpecifications) {
                \array_push($options, $this->newSelectOption($optSpecifications, $newDepth));
            }

            // Finalize option group parameters
            $parameters = ['options' => $options];
            ArrayUtil::merge($specifications, $parameters, ['label', 'disabled']);

            $option = new OptionGroup($parameters);
        }
        else
        {
            // If label is not set, use the value as label
            if (!\array_key_exists('label', $specifications))
            {
                $specifications['label'] = ArrayUtil::get($specifications, 'value');
            }

            $option = new Option($specifications);
        }

        return $option;
    }

    /// PUBLIC METHODS

    /**
     * Set the form group HTML attributes.
     *
     * @param array|null $attributes the html attributes to set for this group builder or null to remove them.
     * @return GroupBuilder the current builder updated.
     */
    public function setAttributes($attributes)
    {
        $this->parameters[self::ATTRIBUTES] = $attributes;

        return $this;
    }

    /**
     * Add an input form control that respect the specifications.
     * 
     * For 'radio' and 'checkbox' type use their specific method.
     * 
     * @param string $name the control name.
     * @param string $type the input type.
     * @param array $options the array of textarea options.
     * @return FormBuilder the current form builder updated.
     */
    public function addInput($name, $type = 'text', $options = [])
    {
        // Create options
        $fieldOptions = $this->createStandardOptions('input', $type, $name, $options);
        // Add input additional options
        $fieldOptions["type"] = $type;

        // Add input control
        \array_push($this->controls, new Input($fieldOptions));

        return $this;
    }

    /**
     * Add a textarea form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of textarea options.
     * @return FormBuilder the current form builder updated.
     */
    public function addTextarea($name, $options)
    {
        // Create options
        $fieldOptions = $this->createStandardOptions('textarea', null, $name, $options);

        \array_push($this->controls, new Textarea($fieldOptions));

        return $this;
    }

    /**
     * Add a select form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of select specifications.
     * @return FormBuilder the current form builder updated.
     */
    public function addSelect($name, $options)
    {
        // Update the option array to create correct constraints
        if (\array_key_exists('multiple', $options) && $options['multiple'] === true)
        {
            $assert = ArrayUtil::getSafe($options, 'assert', []);
            $assert['multiple'] = true;
            $options['assert'] = $assert;
        }

        // Create fieldOptions
        $fieldOptions = $this->createStandardOptions('select', null, $name, $options);

        // Create options from specifications
        $entities = [];
        foreach (ArrayUtil::get($options, 'choices') as $specifications)
        {
            \array_push($entities, $this->newSelectOption($specifications));
        }
        $fieldOptions['options'] = $entities;

        \array_push($this->controls, new Select($fieldOptions));

        return $this;
    }

    /**
     * Add input checkbox form control that respect the specifications.
     *
     * @param string $name the name of the input
     * @param array $options the array of checkbox options.
     * @return FormBuilder the current form builder updated.
     */
    public function addCheckbox($name, $options)
    {
        $fieldOptions = $this->createCommonOptions($name, $options);

        // Control the 'value' key/value pair to set correctly the input value
        if (\array_key_exists('value', $options))
        {
            $value = $options['value'];

            // The value must not be used directly with the checkbox parameters
            // Inject the value in the html attributes
            $fieldOptions[self::ATTRIBUTES]['value'] = $value;
        }
        else
        {
            // By default the value is on
            $value = "on";
        }
        // Mark the checkbox checked or not
        ArrayUtil::merge($options, $fieldOptions, ['checked']);

        if (!\array_key_exists(self::CONSTRAINT, $options))
        {
            // The constraint will be auto build from assert description.
            //  Override the 'equals' constraint to match $value
            $assert = ArrayUtil::getSafe($options, 'assert', []);
            $assert['equals'] = $value;
            $options['assert'] = $assert;
        }

        // Create checkbox constraints
        $this->applyConstraints($fieldOptions, $options, 'input', 'checkbox');

        \array_push($this->controls, new Checkbox($fieldOptions));

        return $this;
    }

    /**
     * Add checkbox group using choices to build each input checkbox.
     *
     * @param string $name the name of the input.
     * @param array $options the array of checkbox group specifications.
     * @return FormBuilder the current builder updated.
     */
    public function addCheckboxGroup($name, $options)
    {
        \array_push(
            $this->controls,
            EntityGroupBuilder::with(CheckboxGroupBuilder::class, $this->constraintBuilder)
                ->setName($name)
                ->build($options),
        );

        return $this;
    }

    /**
     * Add radio group using choices to build each input radio.
     *
     * @param string $name the name of the input.
     * @param array $options the array of radio group specifications.
     * @return FormBuilder the current builder updated.
     */
    public function addRadioGroup($name, $options)
    {
        \array_push(
            $this->controls,
            EntityGroupBuilder::with(RadioGroupBuilder::class, $this->constraintBuilder)
                ->setName($name)
                ->build($options),
        );

        return $this;
    }

    /**
     * Add button html entity set with options.
     *
     * @param string $type the button type.
     * @param array $options the button options.
     * @return FormBuilder the current form builder updated.
     */
    public function addButton($type = Button::TYPE_SUBMIT, $options = [])
    {
        // Create options
        $fieldOptions = $this->createCommonOptions(ArrayUtil::getSafe($options, 'name'), $options);
        $fieldOptions['type'] = $type;

        \array_push($this->controls, new Button($fieldOptions));

        return $this;
    }

    /**
     * Create a new group builder to allows to add a FormGroup.
     *
     * @return GroupBuilder
     */
    public function newGroupBuilder($name)
    {
        $controls = &$this->controls;
        $index = \count($this->controls);

        return new GroupBuilder(
            $name,
            $this,
            function ($formGroup) use ($index, &$controls) {
                // Insert the form group at the creation position
                \array_splice($controls, $index, 0, [$formGroup]);
            },
            $this->constraintBuilder,
        );
    }

    /// TO IMPLEMENT

    /**
     * Build the container.
     *
     * @return FormGroup
     */
    public abstract function build();

}

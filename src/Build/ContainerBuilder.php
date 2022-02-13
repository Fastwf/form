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
use Fastwf\Form\Exceptions\BuildException;
use Fastwf\Form\Entity\Options\OptionGroup;
use Fastwf\Form\Build\Groups\RadioGroupBuilder;
use Fastwf\Form\Build\Groups\EntityGroupBuilder;
use Fastwf\Form\Build\Groups\CheckboxGroupBuilder;

abstract class ContainerBuilder extends AGroupBuilder
{

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
            $params = ['options' => $options];
            ArrayUtil::merge($specifications, $params, ['label', 'disabled']);

            $option = new OptionGroup($params);
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

    /**
     * Create an input form control that respect the specifications.
     * 
     * For 'radio' and 'checkbox' type use their specific method.
     * 
     * @param string $name the control name.
     * @param string $type the input type.
     * @param array $options the array of textarea options.
     * @return Input the form control instance.
     */
    protected function newInput($name, $type = 'text', $options = [])
    {
        // Create options
        $fieldOptions = $this->createStandardOptions('input', $type, $name, $options);
        // Add input additional options
        $fieldOptions["type"] = $type;

        // Create input control
        return new Input($fieldOptions);
    }

    /**
     * Create a textarea form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of textarea options.
     * @return Textarea the form control instance.
     */
    protected function newTextarea($name, $options)
    {
        // Create textarea control
        return new Textarea(
            $this->createStandardOptions(
                'textarea',
                null,
                $name,
                $options
            ),
        );
    }

    /**
     * Create a select form control that respect the specifications.
     *
     * @param string $name the control name.
     * @param array $options the array of select specifications.
     * @return Select the form control instance.
     */
    protected function newSelect($name, $options)
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

        return new Select($fieldOptions);
    }

    /**
     * Create an input checkbox form control that respect the specifications.
     *
     * @param string $name the name of the input
     * @param array $options the array of checkbox options.
     * @return Checkbox the form control instance.
     */
    protected function newCheckbox($name, $options)
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

        return new Checkbox($fieldOptions);
    }

    /**
     * Create a checkbox group using choices to build each input checkbox.
     *
     * @param string $name the name of the input.
     * @param array $options the array of checkbox group specifications.
     * @return CheckboxGroup the form control instance.
     */
    protected function newCheckboxGroup($name, $options)
    {
        return EntityGroupBuilder::with(CheckboxGroupBuilder::class, $this->constraintBuilder)
            ->setName($name)
            ->build($options);
    }

    /**
     * Create a radio group using choices to build each input radio.
     *
     * @param string $name the name of the input.
     * @param array $options the array of radio group specifications.
     * @return RadioGroup the form control instance.
     */
    protected function newRadioGroup($name, $options)
    {
        return EntityGroupBuilder::with(RadioGroupBuilder::class, $this->constraintBuilder)
            ->setName($name)
            ->build($options);
    }

    /**
     * Create a button html entity set with options.
     *
     * @param string $type the button type.
     * @param array $options the button options.
     * @return Button the form control instance.
     */
    protected function newButton($type = Button::TYPE_SUBMIT, $options = [])
    {
        // Create options
        $fieldOptions = $this->createCommonOptions(ArrayUtil::getSafe($options, 'name'), $options);
        $fieldOptions['type'] = $type;

        return new Button($fieldOptions);
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

    /// TO IMPLEMENT

    /**
     * Build the container.
     *
     * @return FormGroup
     */
    public abstract function build();

}

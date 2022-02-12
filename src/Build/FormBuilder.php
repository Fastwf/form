<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Entity\Html\Button;
use Fastwf\Form\Entity\Html\Select;
use Fastwf\Form\Build\AGroupBuilder;
use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Entity\Html\Textarea;
use Fastwf\Form\Entity\Options\Option;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Exceptions\BuildException;
use Fastwf\Form\Entity\Options\OptionGroup;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Form\Build\Groups\RadioGroupBuilder;
use Fastwf\Form\Build\Groups\EntityGroupBuilder;
use Fastwf\Form\Build\Groups\CheckboxGroupBuilder;

/**
 * The builder class pattern that allows to create form entity.
 */
class FormBuilder extends AGroupBuilder
{

    /**
     * The form parameters array.
     *
     * @var array
     */
    private $formParameters;

    /**
     * The list of controls to set in form control.
     *
     * @var array
     */
    private $formControls;

    /**
     * Constructor.
     *
     * @param string $action the action url
     * @param string $method the method to use to submit
     * @param string $encodingType the encoding type of the form (used when the method is Form::METHOD_POST)
     * @param ConstraintBuilder $constraintBuilder the constraint builder to use to add constraints to form controls
     */
    public function __construct($action, $method, $encodingType, $constraintBuilder = null)
    {
        parent::__construct($constraintBuilder);

        $this->formParameters = [
            'action' => $action,
            'method' => $method,
            'enctype' => $encodingType,
        ];
        $this->formControls = [];
    }

    /// PRIVATE METHODS

    /**
     * Generate the field option array of basic form controls.
     *
     * @param string $control the form field type ('input', 'textarea').
     * @param string|null $type when $control is 'input' set the type, else use null.
     * @param string $name the input name.
     * @param array $options the form control build options.
     * @return array the standard field options generated.
     */
    private function createStandardOptions($control, $type, $name, $options)
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
    private function newSelectOption($specifications, $depth = 0)
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
     * Add an input form control that respect the specifications.
     * 
     * For 'radio' and 'checkbox' type use their specific method.
     * 
     * @param string $name the control name.
     * @param string $type the input type.
     * @param array $options the array of textarea options.
     * @return FormBuilder the current form builder updated.
     */
    public function addInput($name, $type = 'text', $options)
    {
        // Create options
        $fieldOptions = $this->createStandardOptions('input', $type, $name, $options);
        // Add input additional options
        $fieldOptions["type"] = $type;

        // Add input control
        \array_push($this->formControls, new Input($fieldOptions));

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

        \array_push($this->formControls, new Textarea($fieldOptions));

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

        \array_push($this->formControls, new Select($fieldOptions));

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

        \array_push($this->formControls, new Checkbox($fieldOptions));

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
            $this->formControls,
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
            $this->formControls,
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

        \array_push($this->formControls, new Button($fieldOptions));

        return $this;
    }

    /**
     * Build the form.
     *
     * @return Form
     */
    public function build()
    {
        $parameters = $this->formParameters;
        $parameters['controls'] = $this->formControls;

        return new Form($parameters);
    }

    /**
     * Create an instance of the form builder.
     *
     * @param string $action the action url
     * @param string $method the method to use to submit
     * @param string $encodingType the encoding type of the form (used when the method is Form::METHOD_POST)
     * @return FormBuilder the builder generated
     */
    public static function new($action, $method = Form::METHOD_GET, $encodingType = Form::FORM_URL_ENCODED)
    {
        return new FormBuilder($action, $method, $encodingType);
    }

}

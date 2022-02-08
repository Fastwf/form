<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Exceptions\KeyError;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\DoubleField;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Constraints\BooleanField;
use Fastwf\Form\Constraints\IntegerField;
use Fastwf\Form\Constraints\RequiredField;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Constraint\Constraints\Required;
use Fastwf\Form\Constraints\Date\DateField;
use Fastwf\Form\Constraints\Date\WeekField;
use Fastwf\Form\Constraints\Time\TimeField;
use Fastwf\Form\Constraints\Date\MonthField;
use Fastwf\Form\Build\Factory\NumericFactory;
use Fastwf\Constraint\Constraints\String\Enum;
use Fastwf\Constraint\Constraints\Arrays\Items;
use Fastwf\Form\Constraints\Date\DateTimeField;
use Fastwf\Form\Constraints\String\ColorFormat;
use Fastwf\Constraint\Constraints\String\Pattern;
use Fastwf\Constraint\Constraints\Type\ArrayType;
use Fastwf\Form\Constraints\String\SplitModifier;
use Fastwf\Constraint\Constraints\Arrays\MinItems;
use Fastwf\Constraint\Constraints\String\MaxLength;
use Fastwf\Constraint\Constraints\String\MinLength;
use Fastwf\Constraint\Constraints\String\UriFormat;
use Fastwf\Constraint\Constraints\Arrays\UniqueItems;
use Fastwf\Constraint\Constraints\String\EmailFormat;

/**
 * Builder class that allows to create constraints and generate associated html 5 validation attributes.
 */
class ConstraintBuilder
{

    /**
     * The constraint key name.
     */
    public const CSTRT = "constraint";
    /**
     * The HTML attribute key name.
     */
    public const ATTRS = "html_attrs";

    /**
     * The form control type.
     *
     * @var string
     */
    private $control = null;
    
    /**
     * The input type.
     *
     * @var string
     */
    private $type = null;

    /**
     * A flag that indicate if the value is required.
     *
     * @var boolean
     */
    private $required = false;

    /**
     * A flag that indicate if multiple is found in constraints.
     *
     * @var boolean
     */
    private $multiple = false;

    /**
     * The primary constraint is the first field constraint that allows to transform the incomming data (it must be StringField
     * constraint or null).
     *
     * @var StringField|null
     */
    private $primaryConstraint = null;

    /**
     * The secondary constraint is the constraint that allows to transform the incomming data after RequiredField constraint validation.
     * 
     * It will not be chained with other constraints (when transformation failed, next constraint must not be validated)
     *
     * @var Constraint|null
     */
    private $secondaryConstraint = null;

    /**
     * The array of constraint to chain in safe mode.
     *
     * @var array
     */
    private $constraints = null;

    /**
     * The array of key/value pair of html attributes associated to added constraints.
     *
     * @var array
     */
    private $htmlAttributes = [];

    /**
     * The array of constraint factory.
     *
     * @var array
     */
    private $factories = [];

    /**
     * Register a new factory for the given factory name.
     * 
     * The callable must expect the next parameters :
     * - formControlName: the name of html form control (select, input, textarea, ...)
     * - inputType: the type name of the control or null for non input form control.
     * - options: the option array to use to create the constraint (key/value pair) or a simple value according to the constraint
     * - allConstraints: the array of unique constraint name associated to it's options
     *
     * @param string $name the unique name corresponding to the constraint.
     * @param callable|array $factory the object callable by \call_user_func.
     * @return ConstraintBuilder the current builder.
     */
    public function register($name, $factory)
    {
        if (\is_callable($factory))
        {
            $callable = $factory;
        }
        else
        {
            // throw error because this factory type is not supported
            throw new ValueError("This factory type is not supported to create constraints");
        }

        $this->factories[$name] = $callable;

        return $this;
    }

    /**
     * Remove the factory associated to the given name (complete silently if the name is not found).
     *
     * @param string $name the unique name of the constraint.
     * @return ConstraintBuilder the current builder.
     */
    public function unregister($name)
    {
        if (\array_key_exists($name, $this->factories))
        {
            unset($this->factories[$name]);
        }

        return $this;
    }

    /**
     * Initialise the builder with the given form control.
     *
     * @param string $control the name of the form control (input, textarea, ...).
     * @param string|null $type the type of the input (text, number, email) or null if the control is not an input.
     * @param array $constraints optional array of unique constraint name associated to it's options (can be required during initialisation).
     * @return ConstraintBuilder the current builder.
     */
    public function from($control, $type = null, $constraints = [])
    {
        // Reset values
        $this->control = $control;
        $this->type = $type;

        $this->required = false;
        $this->multiple = false;
        $this->primaryConstraint = null;
        $this->secondaryConstraint = null;
        $this->constraints = [];
        $this->htmlAttributes = [];

        switch ($control) {
            case 'select':
                $this->fromSelect($constraints);
                break;
            case 'textarea':
                \array_push($this->constraints, new StringField());
                break;
            case 'input':
                // Prepare the basic constraint according to the input type
                $this->fromInput($type, $constraints);
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Prepare the constraint for select input.
     *
     * @param array $asserts optional array of unique constraint name associated to it's options
     * @return void
     */
    private function fromSelect($asserts)
    {
        // For select the constraints will depends on "multiple" attribute
        // When it's multiple the data will be an array, else it's a simple string that respect enum constraint
        if (!\array_key_exists('multiple', $asserts) || !$asserts['multiple'])
        {
            $this->primaryConstraint = new StringField();
        }
    }

    /**
     * Initialize the builder from input type.
     *
     * @param string $type the input type
     * @param array $asserts optional array of unique constraint name associated to it's options
     * @return void
     */
    private function fromInput($type, $asserts)
    {
        // TODO: input file

        $isStringField = false;
        $dataConstraints = [];

        switch ($type)
        {
            case 'color':
                $isStringField = true;
                $dataConstraints = [new ColorFormat()];
                break;
            case 'date':
                $this->secondaryConstraint = new DateField();
                break;
            case 'datetime-local':
                $this->secondaryConstraint = new DateTimeField();
                break;
            case 'email':
                $isStringField = true;
                $dataConstraints = [new EmailFormat()];
                break;
            case 'month':
                $this->secondaryConstraint = new MonthField();
                break;
            case 'number':
            case 'range':
                // Analyse the step constraint to know how to convert the field
                $matches = [];
                if (\array_key_exists('step', $asserts)
                    && \preg_match("/^\d+\\.(\d+)$/", $asserts['step'], $matches)
                    && ((int) $matches[1]) !== 0)
                {
                    // The step format match a float definition, the field must be converted to Double
                    $this->secondaryConstraint = new DoubleField();
                }
                else
                {
                    // In other cases (no step, bad step format, ...), the value will be converted as Integer
                    $this->secondaryConstraint = new IntegerField();
                }
                break;
            case 'time':
                $this->secondaryConstraint = new TimeField();
                break;
            case 'url':
                $isStringField = true;
                $dataConstraints = [new UriFormat()];
                break;
            case 'week':
                $this->secondaryConstraint = new WeekField();
                break;
            case 'checkbox':
                // Control the 'equals' constraint to know how to initialize constraints
                if (\array_key_exists('equals', $asserts) && !\in_array($asserts['equals'], BooleanField::POSITIVE_VALUES))
                {
                    // Require to validate a string value
                    $isStringField = true;
                }
                else
                {
                    $this->secondaryConstraint = new BooleanField();
                }
                break;
            default:
                // password, search, tel, text, ...
                $isStringField = true;
                break;
        }

        // Add the primary string field convertion constraint
        if ($isStringField)
        {
            $this->primaryConstraint = new StringField();
        }

        \array_push($this->constraints, ...$dataConstraints);
    }

    /**
     * Add a new constraint.
     *
     * @param string $name the unique constraint name.
     * @param mixed $args the constraint parameters.
     * @param array $constraints the array of unique constraint name associated to it's options.
     * @return ConstraintBuilder the current builder.
     */
    public function add($name, $args, $constraints)
    {
        switch ($name) {
            case 'required':
                $this->required = true;
                $this->htmlAttributes['required'] = true;
                break;
            case 'multiple':
                $this->multiple = true;
                $this->htmlAttributes['multiple'] = true;
                break;
            default:
                // Use pre registered factory callable to create an instance of the constraint 
                if (!\array_key_exists($name, $this->factories))
                {
                    throw new KeyError("No factory found for '$name' constraint id");
                }
                else
                {
                    $result = \call_user_func($this->factories[$name], $this->control, $this->type, $args, $constraints);
                    
                    // Constraint must be provided, no control are performed
                    \array_push($this->constraints, $result[self::CSTRT]);
                    // Additionnal html attributes are not required, control the result key
                    if (\array_key_exists(self::ATTRS, $result))
                    {
                        $this->htmlAttributes = \array_merge($this->htmlAttributes, $result[self::ATTRS]);
                    }
                }
                break;
        }

        return $this;
    }

    /**
     * Build the global constraint using default build system.
     *
     * @return Constraint the global constraint.
     */
    private function buildCommonSystem()
    {
        // Chain all constraints as global constraint
        // Create the chain of secondary and final safe constraints.
        $subConstraints = null;

        if ($this->constraints)
        {
            $subConstraints = new Chain(false, ...$this->constraints);
        }

        if ($this->secondaryConstraint)
        {
            if ($subConstraints === null)
            {
                $subConstraints = $this->secondaryConstraint;
            }
            else
            {
                // Chain not safe the secondary constraint with other safe constraints
                $subConstraints = new Chain(true, $this->secondaryConstraint, $subConstraints);
            }
        }

        // Create the required field constraint
        $requiredConstraint = new RequiredField($this->required, $subConstraints);

        return $this->primaryConstraint === null
            ? $requiredConstraint
            : new Chain(true, $this->primaryConstraint, $requiredConstraint);
    }

    /**
     * Allows to build the constraints for input email with multiple attribute.
     *
     * @return Constraint the constraint.
     */
    private function buildMultiEmailSystem()
    {
        // Build multiple constraint,
        //  Perform split modification and than each items will be validated with defined constraints
        $subConstraint = new Chain(
            false,
            new SplitModifier(
                ",",
                true,
                new Chain(false, ...$this->constraints),
            ),
        );

        return new Chain(
            true,
            $this->primaryConstraint,
            new RequiredField($this->required, $subConstraint),
        );
    }

    /**
     * Build the constraint for select with multiple attribute.
     *
     * @return Constraint the select constraint.
     */
    private function buildMultiSelectSystem()
    {
        // Create the safe array constraints
        $arrayConstraints = $this->required ? [new MinItems(1)] : [];
        \array_push(
            $arrayConstraints,
            new Items(
                new Chain(false, ...$this->constraints)
            ),
            new UniqueItems(),
        );

        // Create the non safe array constraint
        $constraint = new Chain(
            true,
            new ArrayType(),
            new Chain(false, ...$arrayConstraints),
        );

        // For required, check that the value is null and the number of items is at least 1
        return new Nullable(!$this->required, $constraint);
    }

    /**
     * Build the constraint for input[checkbox] control.
     *
     * @return Constraint the checkbox constraint.
     */
    private function buildCheckboxSystem()
    {
        if ($this->secondaryConstraint !== null)
        {
            // The BooleanField constraint is set, the global constraint must validate a boolean value
            //  This is used for control <input type="checkbox" value="on" ...>
            $globalConstraint = new Nullable(!$this->required, $this->secondaryConstraint);
        }
        else
        {
            // The checkbox must validate that the value is set and correspond to the reference value
            //  This is used for control <input type="checkbox" value="any string value" ...>
            $subConstraint = new Chain(false, ...$this->constraints);
            $globalConstraint = new Chain(true, $this->primaryConstraint, new RequiredField($this->required, $subConstraint));
        }

        return $globalConstraint;
    }

    /**
     * Build the constraint for group or input[radio] control.
     *
     * @return Constraint the radio group constraint.
     */
    private function buildRadioGroupSystem()
    {
        // Build all constraints (for radio group, normally, it requires only enum constraint)
        $subConstraint = new Chain(false, ...$this->constraints);

        return new Nullable(!$this->required, $subConstraint);
    }

    /**
     * Build the constraint described by given constraints
     *
     * @return array an array containing:
     *                * 'constraint' key: the constraint
     *                * 'html_attrs' key: an associative array containing html attributes to use for HTML 5 validation
     */
    public function build()
    {
        if ($this->multiple)
        {
            // When multiple flag is activated, override the default constraint build system.
            if ($this->control === 'select')
            {
                $globalConstraint = $this->buildMultiSelectSystem();
            }
            else if ($this->control === 'input' && $this->type === 'email')
            {
                // Build multiple constraint for email
                $globalConstraint = $this->buildMultiEmailSystem();
            }
            // TODO: input[file]
        }
        else if ($this->control === 'radio-group')
        {
            $globalConstraint = $this->buildRadioGroupSystem();
        }
        else if ($this->control === 'input' && $this->type === 'checkbox')
        {
            // Build the final checkbox constraint.
            $globalConstraint = $this->buildCheckboxSystem();
        }
        else
        {
            // By default use the common build system
            $globalConstraint = $this->buildCommonSystem();
        }

        return [
            self::CSTRT => $globalConstraint,
            self::ATTRS => $this->htmlAttributes,
        ];
    }

    /**
     * Create the constraint builder set with default HTML constraints.
     *
     * @return ConstraintBuilder the builder ready to use.
     */
    public static function getDefault()
    {
        $builder = new ConstraintBuilder();

        // Basic string constraints
        $builder->register('minLength', function ($_1, $_2, $length, $_3) {
                // Expect a length as options
                return [self::CSTRT => new MinLength($length), self::ATTRS => ["minlength" => $length]];
            })
            ->register('maxLength', function ($_1, $_2, $length, $_3) {
                // Expect a length as options
                return [self::CSTRT => new MaxLength($length), self::ATTRS => ["maxlength" => $length]];
            })
            ->register('pattern', function ($_1, $_2, $pattern, $_3) {
                // Expect a pattern as options
                return [self::CSTRT => new Pattern($pattern), self::ATTRS => ["pattern" => $pattern]];
            })

            // Basic number constraints
            ->register('min', function ($control, $type, $min, $_1) {
                // Expect a minimum value (int, double, string or \DateTime) as options
                return NumericFactory::of($control, $type, 'min')->min($min);
            })
            ->register('max', function ($control, $type, $max, $_1) {
                // Expect a minimum value (int, double, string or \DateTime) as options
                return NumericFactory::of($control, $type, 'max')->max($max);
            })
            ->register('step', function ($control, $type, $step, $constraints) {
                // Expect a step value (int or double) as options
                return NumericFactory::of($control, $type, 'step')->step($step, $constraints);
            })

            ->register('enum', function ($_1, $_2, $enum, $_3) {
                // Expect an array of string as enum
                return [self::CSTRT => new Enum($enum)];
            })
            ->register('equals', function ($_1, $_2, $value, $_3) {
                // Constraint that check if the value provided is the same $value parameter
                return [self::CSTRT => new Equals($value)];
            })
            ;

        return $builder;
    }

}

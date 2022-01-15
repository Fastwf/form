<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Exceptions\KeyError;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Constraint\Constraints\Required;
use Fastwf\Form\Constraints\Date\DateField;
use Fastwf\Form\Build\Factory\NumericFactory;
use Fastwf\Form\Constraints\Date\DateTimeField;
use Fastwf\Constraint\Constraints\String\Pattern;
use Fastwf\Constraint\Constraints\String\MaxLength;
use Fastwf\Constraint\Constraints\String\MinLength;

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
     * A flag that indicate if the value is nullable.
     *
     * @var boolean
     */
    private $nullable = true;

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
     * @return ConstraintBuilder the current builder.
     */
    public function from($control, $type = null)
    {
        // Reset values
        $this->control = $control;
        $this->type = $type;

        $this->required = false;
        $this->nullable = true;
        $this->constraints = [];
        $this->htmlAttributes = [];

        switch ($control) {
            case 'select':
                # code...
                break;
            case 'textarea':
                \array_push($this->constraints, new StringField());
                break;
            case 'input':
                // Prepare the basic constraint according to the input type
                $this->fromInput($type);
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Initialize the builder from input type.
     *
     * @param string $type the input type
     * @return void
     */
    private function fromInput($type)
    {
        switch ($type)
        {
            case 'date':
                \array_push($this->constraints, new DateField());
                break;
            case 'datetime-local':
                \array_push($this->constraints, new DateTimeField());
                break;
            default:
                break;
        }
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
            case 'nullable':
                $this->nullable = true;
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
                    
                    \array_push($this->constraints, $result[self::CSTRT]);
                    $this->htmlAttributes = \array_merge($this->htmlAttributes, $result[self::ATTRS]);
                }
                break;
        }

        return $this;
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
        // Chain required / nullable and other constraints as global constraint
        $cChain = new Chain(false, ...$this->constraints);
        $cNullable = new Nullable($this->nullable, $cChain);

        return [
            self::CSTRT => new Required($this->required, $cNullable),
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
            });
        
        // Email => multiple ?

        return $builder;
    }

}

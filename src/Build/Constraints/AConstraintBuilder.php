<?php

namespace Fastwf\Form\Build\Constraints;

use Fastwf\Form\Exceptions\KeyError;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\RequiredField;

/**
 * Base constraint builder that define the common/default logic for building form control constraint and corresponding html attributes.
 */
abstract class AConstraintBuilder
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
     * The parent builder of the constraint builder instance.
     *
     * @var AConstraintBuilder|null
     */
    protected $parent;

    /**
     * The array of constraint factory.
     *
     * @var array
     */
    protected $factories = [];

    /// Build state properties

    /**
     * The form control type.
     *
     * @var string
     */
    protected $control = null;
    
    /**
     * The input type.
     *
     * @var string
     */
    protected $type = null;

    /**
     * A flag that indicate if the value is required.
     *
     * @var boolean
     */
    protected $required = false;

    /**
     * The array of constraint to chain in safe mode.
     *
     * @var array
     */
    protected $constraints = null;

    /**
     * The array of key/value pair of html attributes associated to added constraints.
     *
     * @var array
     */
    protected $htmlAttributes = [];

    /// PROTECTED METHODS

    /**
     * Initialise the builder with the given form control.
     *
     * @param string $control the name of the form control (input, textarea, ...).
     * @param string|null $type the type of the input (text, number, email) or null if the control is not an input.
     * @param array $constraints optional array of unique constraint name associated to it's options (can be required during initialisation).
     */
    protected function resetFrom($control, $type, $constraints)
    {
        // Reset values
        $this->control = $control;
        $this->type = $type;

        $this->required = false;
        $this->constraints = [];
        $this->htmlAttributes = [];
    }

    /**
     * Create a new constraint from constraint name and its parameters.
     *
     * @param string $name the unique constraint name.
     * @param mixed $args the constraint parameters.
     * @param array $constraints the array of unique constraint name associated to it's options.
     * @return void
     * @throws KeyError when the constraint cannot be built.
     */
    protected function factoryConstraint($name, $args, $constraints)
    {
        if ($name === 'required')
        {
            // $args are ignored
            $this->required = true;
            $this->htmlAttributes['required'] = true;
        }
        else
        {
            // By default call the constraint factory callable to build the constraint and update HTML attributes
            $params = \call_user_func(
                $this->getFactory($name, false),
                $this->control,
                $this->type,
                $args,
                $constraints
            );
            
            // Add the constraint object when it is set in $params
            if (\array_key_exists(self::CSTRT, $params))
            {
                \array_push($this->constraints, $params[self::CSTRT]);
            }
    
            // Additionnal html attributes are not required, control the $params key
            if (\array_key_exists(self::ATTRS, $params))
            {
                $this->htmlAttributes = \array_merge($this->htmlAttributes, $params[self::ATTRS]);
            }
        }
    }

    /**
     * Build the constraint to push inside a RequiredField.
     *
     * @return Constraint the constraint built thanks to this builder context.
     */
    protected function buildConstraints()
    {
        return self::chainConstraints($this->constraints, false);
    }

    /**
     * Build the entry constraint to set on form control.
     * 
     * This constraint is composed of all added constraints.
     *
     * @return Constraint the global constraint.
     */
    protected function buildEntryConstraint()
    {
        $chainedConstraints = $this->buildConstraints();

        // Inject the chained constraints in the required field constraint
        return new RequiredField($this->required, $chainedConstraints);
    }

    /// PUBLIC METHODS

    /**
     * Set the parent builder of this builder.
     *
     * @param AConstraintBuilder|null $parent the parent builder instance or null.
     * @return $this the current builder.
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

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
     * @param callable|array|null $factory the object callable by \call_user_func.
     * @return $this the current builder.
     */
    public function setFactory($name, $factory)
    {
        if ($factory === null)
        {
            unset($this->factories[$name]);
        }
        else if (\is_callable($factory))
        {
            $this->factories[$name] = $factory;
        }
        else
        {
            // throw error because this factory type is not supported
            throw new ValueError("This factory type is not supported to create constraints");
        }

        return $this;
    }

    /**
     * Get the factory callable associated to the unique name corresponding to the constraint. 
     *
     * @param string the unique name constraint.
     * @param boolean $safe true to return null, false to throw KeyError.
     * @return callable|array the callable factory.
     * @throws KeyError when no factory are found in unsafe mode.
     */
    public function getFactory($name, $safe = true)
    {
        $factory = null;

        // Use the factory attached to this builder.
        //  When this builder have not factory, try to get the parent's.
        //  Finally if no factory are found and it's not in safe mode, throw KeyError
        if (\array_key_exists($name, $this->factories))
        {
            $factory = $this->factories[$name];
        }
        else if ($this->parent !== null)
        {
            $factory = $this->parent->getFactory($name);
        }
        else if (!$safe)
        {
            throw new KeyError("No factory found for '$name' constraint id");
        }
        
        return $factory;
    }

    /**
     * Add a new constraint.
     *
     * @param string $name the unique constraint name.
     * @param mixed $args the constraint parameters.
     * @param array $constraints the array of unique constraint name associated to it's options.
     * @return $this the current builder.
     */
    public final function add($name, $args, $constraints)
    {
        $this->factoryConstraint($name, $args, $constraints);

        return $this;
    }

    /**
     * Initialise the builder with the given form control.
     *
     * @param string $control the name of the form control (input, textarea, ...).
     * @param string|null $type the type of the input (text, number, email) or null if the control is not an input.
     * @param array $constraints optional array of unique constraint name associated to it's options (can be required during initialisation).
     * @return AConstraintBuilder the current builder or a sub builder corresponding to the form control.
     */
    public function from($control, $type = null, $constraints = [])
    {
        // Reset values
        $this->resetFrom($control, $type, $constraints);

        // By default return the current constraint builder
        return $this;
    }

    /**
     * Build the constraint described by given constraints
     *
     * @return array an array containing:
     *                * 'constraint' key: the constraint
     *                * 'html_attrs' key: an associative array containing html attributes to use for HTML 5 validation
     */
    public final function build()
    {
        return [
            self::CSTRT => $this->buildEntryConstraint(),
            self::ATTRS => $this->htmlAttributes,
        ];
    }

    /// STATIC METHODS

    /**
     * Get the control name according to field and its type (when it's an input).
     *
     * @param string $control the html entity field (input, textarea, select) or any custom element.
     * @param string|null $type the input type or null.
     * @return string the form control unique name.
     */
    protected static function getControlName($control, $type = null)
    {
        return $type === null ? $control : "${control}[${type}]";
    }

    /**
     * Analyse the constraint array and return null, the alone constraint or a Chain constraint composed of all constraints of the array
     * parameter.
     *
     * @param array $constraints the array of all constraints
     * @param boolean $stopOnFirst true when the constraint must stop the validation on first violation.
     * @return void
     */
    protected static function chainConstraints($constraints, $stopOnFirst)
    {
        // When $this->constraints !== null && !empty($this->constraints) 
        if ($constraints)
        {
            // When there are more than 1 constraint, chain the constraints in safe mode
            //  otherwise return the alone constraint.
            return \array_key_exists(1, $constraints)
                ? new Chain($stopOnFirst, ...$constraints)
                : $constraints[0];
        }

        return null;
        
    }

}

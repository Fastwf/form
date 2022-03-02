<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Build\ConstraintBuilder;

/**
 * Common properties and methods to use to build a group of controls.
 */
abstract class AGroupBuilder
{

    /**
     * The option key for html attributes.
     */
    protected const ATTRIBUTES = "attributes";

    /**
     * The option key for constraint option.
     */
    protected const CONSTRAINT = 'constraint';

    /**
     * The option key for label option.
     */
    protected const LABEL = 'label';

    /**
     * The option key for help option.
     */
    protected const HELP = 'help';

    /**
     * The common options names.
     */
    private const COMMON_OPTIONS = [
        self::ATTRIBUTES,
        "disabled",
        "readonly",
        self::LABEL,
        self::HELP,
    ];

    /**
     * The constraint buidler to use to prepare constraint when assert option is provided.
     *
     * @var ConstraintBuilder
     */
    protected $constraintBuilder;

    /**
     * The array of parameters to use to create instance of Container.
     *
     * @var array<string,mixed>
     */
    protected $parameters;

    /**
     * Constructor.
     * 
     * 
     * $options:
     * - `constraintBuilder` (default null): The constraint builder to use to add constraints to form controls  
     * - `attributes` (default []) : The array of html attributes  
     * - `label` (default "string") : The string label of the group  
     * - `help` (default "string") : The string help message of the group  
     * 
     * For more details on options {@see Control::__construct} parameters.
     * 
     * @param array{constraintBuilder?:ConstraintBuilder,attributes:array,label?:string,help?:string} $options the base group options.
     */
    public function __construct($options = [])
    {
        $constraintBuilder = ArrayUtil::getSafe($options, 'constraintBuilder');

        $this->constraintBuilder = $constraintBuilder === null ? ConstraintBuilder::getDefault() : $constraintBuilder;

        $this->parameters = [];
        ArrayUtil::merge($options, $this->parameters, [self::ATTRIBUTES, self::LABEL, self::HELP]);
    }

    /**
     * Generate the common option array of all form controls
     *
     * @param string $name the input name
     * @param array $options the form control build options
     * @param array $commonOptions the array of common options (self::COMMON_OPTIONS by default)
     * @return array
     */
    protected function createCommonOptions($name, $options, $commonOptions = self::COMMON_OPTIONS)
    {
        $fieldOptions = [
            "name" => $name,
        ];

        ArrayUtil::merge($options, $fieldOptions, $commonOptions);

        // Create a default html attribute array
        if (!\array_key_exists(self::ATTRIBUTES, $fieldOptions))
        {
            $fieldOptions[self::ATTRIBUTES] = [];
        }

        return $fieldOptions;
    }

    /**
     * Inject constraint in field options from form control options.
     *
     * The constraint is used directly using 'constraint' option.  
     * When constraint is not provided and 'assert' option is set, the constraint is built from assert specifications.
     * 
     * @param array $fieldOptions (out) the field options to update.
     * @param array $options the array of form control options.
     * @param string $control the form control ('input', 'textarea', 'select')
     * @param string|null $type when $control is 'input', the input type else set to null
     * @return void
     */
    protected function applyConstraints(&$fieldOptions, $options, $control, $type = null)
    {
        // The 'constraint' key have the priority on 'assert'
        if (\array_key_exists(self::CONSTRAINT, $options))
        {
            $fieldOptions[self::CONSTRAINT] = $options[self::CONSTRAINT];
        }
        else if (\array_key_exists('assert', $options))
        {
            // Convert the 'assert' key, it's parameters and form control type as constraint
            // Apply corresponding html attributes
            $asserts = $options['assert'];
            // The builder can return itself or a sub builder more specific to the current $control/$type form field
            $builder = $this->constraintBuilder->from($control, $type, $asserts);

            foreach ($asserts as $name => $parameters)
            {
                $builder->add($name, $parameters, $asserts);
            }

            $result = $builder->build();

            // Inject constraints and update html attributes
            $fieldOptions[self::CONSTRAINT] = $result[ConstraintBuilder::CSTRT];
            $fieldOptions[self::ATTRIBUTES] = \array_merge(
                $fieldOptions[self::ATTRIBUTES],
                $result[ConstraintBuilder::ATTRS],
            );
        }
    }

}

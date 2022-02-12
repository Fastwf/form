<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Exceptions\BuildException;

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
     * The common options names.
     */
    private const COMMON_OPTIONS = [
        self::ATTRIBUTES,
        "disabled",
        "readonly",
        "label",
        "help",
    ];

    /**
     * The constraint buidler to use to prepare constraint when assert option is provided.
     *
     * @var ConstraintBuilder
     */
    protected $constraintBuilder;

    /**
     * Constructor.
     * 
     * @param ConstraintBuilder $constraintBuilder the constraint builder to use to add constraints to form controls
     */
    public function __construct($constraintBuilder = null)
    {
        $this->constraintBuilder = $constraintBuilder === null ? ConstraintBuilder::getDefault() : $constraintBuilder;
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
            $this->constraintBuilder->from($control, $type, $asserts);

            foreach ($asserts as $name => $parameters)
            {
                $this->constraintBuilder->add($name, $parameters, $asserts);
            }

            $result = $this->constraintBuilder->build();

            // Inject constraints and update html attributes
            $fieldOptions[self::CONSTRAINT] = $result[ConstraintBuilder::CSTRT];
            $fieldOptions[self::ATTRIBUTES] = \array_merge(
                $fieldOptions[self::ATTRIBUTES],
                $result[ConstraintBuilder::ATTRS],
            );
        }
    }

}

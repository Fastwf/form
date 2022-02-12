<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Build\AGroupBuilder;
use Fastwf\Form\Build\ConstraintBuilder;
use Fastwf\Form\Entity\Html\CheckableInput;
use Fastwf\Form\Entity\Containers\EntityGroup;

/**
 * Common code for group builder that allows to create group widget based on its specifications.
 */
abstract class EntityGroupBuilder extends AGroupBuilder
{

    /**
     * The name of the radio group to build.
     *
     * @var string
     */
    protected $name;

    /**
     * The array of named arguments of the RadioGroup.
     *
     * @var array
     */
    protected $groupOptions;

    /**
     * The array containing html attributes and the constraint object associated to the entity group and its form controls.
     *
     * @var array
     */
    protected $constraintControlOptions;

    /**
     * Constructor.
     * 
     * @param ConstraintBuilder $constraintBuilder the constraint builder to use to add constraints to form controls
     */
    public function __construct($constraintBuilder = null)
    {
        parent::__construct($constraintBuilder);
    }

    /**
     * Prepare the builder from the entity group specification.
     *
     * @param array $options the reference to the array of entity group specifications.
     * @return void
     */
    protected function setup(&$options)
    {
        // Initialize entity group options
        $this->groupOptions = ['name' => $this->name];
        ArrayUtil::merge($options, $this->groupOptions, [self::ATTRIBUTES, "help"]);

        // Create assert if it is required for value accepted
        $assert = ArrayUtil::getSafe($options, 'assert', []);
        if (!\array_key_exists('enum', $assert))
        {
            $assert['enum'] = \array_map(
                function ($item) {
                    return ArrayUtil::get($item, 'value');
                },
                ArrayUtil::get($options, 'choices')
            );
            $options['assert'] = $assert;
        }

        // Collect control attributes set by constraint builder
        $this->constraintControlOptions = [self::ATTRIBUTES => []];
        $this->applyConstraints($this->constraintControlOptions, $options, $this->getWidgetName());

        // Constraints will be applayed only on entity group
        $this->groupOptions[self::CONSTRAINT] = $this->constraintControlOptions[self::CONSTRAINT];
    }

    /**
     * Build form controls using entity group and choice specifications.
     *
     * @param array $options the reference to the array of entity group specifications.
     * @return array the array of form control built from specifications.
     */
    protected function buildControls(&$options)
    {
        // Create all radio input of the group
        $controls = [];
        foreach (ArrayUtil::get($options, 'choices') as $specifications)
        {
            $fieldOptions = $this->createCommonOptions(
                $this->name,
                \array_merge(ArrayUtil::getSafe($options, 'common', []), $specifications),
            );
            // Inject html attributes created by constraint builder into field options
            $fieldOptions[self::ATTRIBUTES] = \array_merge(
                $fieldOptions[self::ATTRIBUTES],
                $this->constraintControlOptions[self::ATTRIBUTES]
            );

            // Set the value as html attribute
            $value = ArrayUtil::get($specifications, 'value');
            $fieldOptions[self::ATTRIBUTES]['value'] = $value;

            // Set the label is is not set using radio value
            if (!\array_key_exists('label', $fieldOptions))
            {
                $fieldOptions['label'] = $value;
            }

            \array_push($controls, $this->buildFormControl($fieldOptions));
        }

        return $controls;
    }

    /**
     * Set the name of the entity group widget.
     *
     * @param string $name the name of the group to build.
     * @return EntityGroupBuilder the builder updated.
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Build the entity group from its specifications.
     *
     * @param array $options the array of entity group specifications.
     * @return EntityGroup the entity group generated.
     */
    public function build($options)
    {
        $this->setup($options);

        $this->groupOptions['controls'] = $this->buildControls($options);

        // Build the group and assign the default value when it's provided
        $group = $this->buildEntityGroup($this->groupOptions);
        if (\array_key_exists('defaultValue', $options))
        {
            $group->setValue($options['defaultValue']);
        }

        return $group;
    }

    /// TO IMPLEMENTS

    /**
     * Get the name of the widget. This name will be used to build correctly widget constraints.
     *
     * @return string
     */
    protected abstract function getWidgetName();

    /**
     * Build the form control from its specifications.
     *
     * @param array $options the form control specifications.
     * @return Control the form control
     */
    protected abstract function buildFormControl($options);

    /**
     * Build the entity group from safe parameter array.
     *
     * @param array $options the array of parameters to use to create entity group instance.
     * @return EntityGroup the entity group generated.
     */
    protected abstract function buildEntityGroup(&$options);

    /// STATIC METHODS

    /**
     * Create an instance of the entity group builder using the widget name and the builder class.
     *
     * @param string $class the class to construct.
     * @param ConstraintBuilder $constraintBuilder the constraint builder of the parent or null to use default instance.
     * @return EntityGroupBuilder the buider instanciated.
     */
    public static function with($class, $constraintBuilder = null)
    {
        return new $class($constraintBuilder);
    }

}

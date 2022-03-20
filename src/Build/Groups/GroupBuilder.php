<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Build\FormBuilder;
use Fastwf\Form\Build\ContainerBuilder;
use Fastwf\Form\Build\Groups\ArrayBuilder;
use Fastwf\Form\Build\ContainerGroupBuilder;
use Fastwf\Form\Entity\Containers\FormGroup;
use Fastwf\Api\Utils\ArrayUtil;

/**
 * The builder able to generate FormGroup containers.
 */
class GroupBuilder extends ContainerGroupBuilder
{

    /**
     * The parent builder.
     *
     * @var ContainerBuilder
     */
    protected $builder;

    /**
     * The callback to call when this builder call the "buildInParent" method.
     *
     * @var Callable<FormGroup>
     */
    protected $onBuildCallback;
    
    /**
     * {@inheritDoc}
     *
     * @param string $name the group name.
     * @param ContainerBuilder $builder the parent builder name
     * @param array{
     *      constraintBuilder?:ConstraintBuilder,
     *      onBuildCallback?: Callable<FormGroup>
     * } $options
     */
    public function __construct($name, $builder, $options = [])
    {
        parent::__construct($name, $options);

        $this->builder = $builder;
        $this->onBuildCallback = ArrayUtil::getSafe($options, 'onBuildCallback');
    }

    /// IMPLEMENTATION

    /**
     * Generate the FormGroup from the builder specifications.
     *
     * @return FormGroup
     */
    public function build()
    {
        $this->parameters['controls'] = $this->controls;

        return new FormGroup($this->parameters);
    }

    /// PUBLIC METHODS

    /**
     * Build the FormGroup from current specifications, add the form group build then return the parent builder.
     *
     * @return FormBuilder|GroupBuilder|ArrayBuilder the parent group builder.
     */
    public function buildInParent()
    {
        $formGroup = $this->build();

        if (\is_callable($this->onBuildCallback))
        {
            \call_user_func($this->onBuildCallback, $formGroup);
        }

        return $this->builder;
    }

}

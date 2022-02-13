<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Build\ContainerBuilder;
use Fastwf\Form\Build\Groups\ArrayBuilder;
use Fastwf\Form\Build\ContainerGroupBuilder;
use Fastwf\Form\Entity\Containers\FormGroup;

/**
 * The builder able to generate FormGroup containers.
 */
class GroupBuilder extends ContainerGroupBuilder
{

    /**
     * The parent builder.
     *
     * @var GroupBuilder|ArrayBuilder
     */
    protected $builder;

    /**
     * The callback to call when this builder call the "buildInParent" method.
     *
     * @var callable
     */
    protected $onBuildCallback;
    
    public function __construct($name, $builder, $onBuildCallback = null, $constraintBuilder = null)
    {
        parent::__construct($name, $constraintBuilder);

        $this->builder = $builder;
        $this->onBuildCallback = $onBuildCallback;
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
     * @return GroupBuilder|ArrayBuilder the parent group builder.
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

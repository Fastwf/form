<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Entity\Html\Checkbox;
use Fastwf\Form\Build\Groups\EntityGroupBuilder;
use Fastwf\Form\Entity\Containers\CheckboxGroup;

/**
 * Group builder that allows to create a checkbox group widget based on its specifications.
 */
class CheckboxGroupBuilder extends EntityGroupBuilder
{

    /// IMPLEMENTS METHODS

    protected function getWidgetName()
    {
        return 'checkbox-group';
    }

    protected function buildFormControl($options)
    {
        return new Checkbox($options);
    }

    protected function buildEntityGroup(&$options)
    {
        return new CheckboxGroup($options);
    }

}

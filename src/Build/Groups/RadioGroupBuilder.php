<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Entity\Html\Radio;
use Fastwf\Form\Entity\Containers\RadioGroup;
use Fastwf\Form\Build\Groups\EntityGroupBuilder;

/**
 * Group builder that allows to create a radio group widget based on its specifications.
 */
class RadioGroupBuilder extends EntityGroupBuilder
{

    /// IMPLEMENTS METHODS

    protected function getWidgetName()
    {
        return 'radio-group';
    }

    protected function buildFormControl($options)
    {
        return new Radio($options);
    }

    protected function buildEntityGroup(&$options)
    {
        return new RadioGroup($options);
    }

}

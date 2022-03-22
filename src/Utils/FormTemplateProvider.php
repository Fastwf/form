<?php

namespace Fastwf\Form\Utils;

use Fastwf\Constraint\Api\SimpleTemplateProvider;

/**
 * Extension of the SimpleTemplateProvider that provide default strings to interpolate for froms errors.
 */
class FormTemplateProvider extends SimpleTemplateProvider
{

    public function __construct()
    {
        $this->templates = \array_merge(
            $this->templates,
            [
                // Fields constraints
                'field-required' => 'This field is required',
                'field-string' => 'The value must be a valid char sequence',
                'field-integer' => 'The value must be an integer',
                'field-double' => 'The value must be a floating point number',
                'field-boolean' => 'The value must be TRUE or FALSE',
                'field-date' => 'The value must be a valid date',
                'field-datetime' => 'The value must be a valid date time',
                'month-field' => 'The value must be a valid month',
                'week-field' => 'The value must be a valid week',
                'time-field' => 'The value must be a valid time',
                'color-field' => 'The value must be a valid color',
                // String constraints
                'equals' => 'The value is not equals to the expected value',
                // Number constraints
                'step' => 'The value must respect the step of %{step}',
                // Date constraints
                'min-datetime' => 'The value must be greater or equals to %{dateTime|fmtDate(type)}',
                'max-datetime' => 'The value must be lower or equals to %{dateTime|fmtDate(type)}',
                'step-datetime' => 'The value must respect the step of %{step} second(s)',
                'step-month' => 'The value must respect the step of %{step} month(s)',
                // Time constraints
                'min-time' => 'The time must be greater or equals to %{time|fmtTime}',
                'max-time' => 'The time must be lower or equals to %{time|fmtTime}',
                'step-time' => 'The value must respect the step of %{step} second(s)',
            ]
        );
    }

}

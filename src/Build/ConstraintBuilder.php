<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Constraint\Constraints\String\Enum;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;
use Fastwf\Form\Build\Constraints\String\UrlConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\DateConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\TimeConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\WeekConstraintBuilder;
use Fastwf\Form\Build\Constraints\String\ColorConstraintBuilder;
use Fastwf\Form\Build\Constraints\String\EmailConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\MonthConstraintBuilder;
use Fastwf\Form\Build\Constraints\String\StringConstraintBuilder;
use Fastwf\Form\Build\Constraints\Widget\SelectConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\NumberConstraintBuilder;
use Fastwf\Form\Build\Constraints\Widget\CheckboxConstraintBuilder;
use Fastwf\Form\Build\Constraints\Numeric\DateTimeConstraintBuilder;
use Fastwf\Form\Build\Constraints\Widget\RadioGroupConstraintBuilder;
use Fastwf\Form\Build\Constraints\Widget\CheckboxGroupConstraintBuilder;

/**
 * Builder class that allows to create constraints and generate associated html 5 validation attributes.
 * 
 * By default it is a basic StringConstraintBuilder.
 */
class ConstraintBuilder extends StringConstraintBuilder
{

    /**
     * The array of sub builders to use for specific form controls.
     *
     * @var array
     */
    protected $builders = [];

    /**
     * Get the sub builder associated to the given form control definition.
     *
     * @param AConstraintBuilder|null the sub builder to set for the given field definition or null to remove the previous.
     * @param string $control the html entity field (input, textarea, select) or any custom element.
     * @param string|null $type the input type or null.
     * @return $this
     */
    public function setBuilder($builder, $control, $type = null)
    {
        $key = self::getControlName($control, $type);

        if ($builder === null)
        {
            // Remove the builder when it's required
            if (\array_key_exists($key, $this->builders))
            {
                unset($this->builders[$key]);
            }
        }
        else
        {
            // Add or replace the previous builder by the new builder
            $this->builders[$key] = $builder->setParent($this);
        }

        return $this;
    }

    /**
     * Get the sub builder associated to the given form control definition.
     *
     * @param string $control the html entity field (input, textarea, select) or any custom element.
     * @param string|null $type the input type or null.
     * @return AConstraintBuilder|null the sub builder or null when no builder are set for the given field definition.
     */
    public function getBuilder($control, $type = null)
    {
        ArrayUtil::getSafe($this->builders, self::getControlName($control, $type));
    }

    public function from($control, $type = null, $constraints = [])
    {
        $key = self::getControlName($control, $type);

        // Return an initialized sub builder
        //  or return the buider of the parent logic
        return \array_key_exists($key, $this->builders)
            ? $this->builders[$key]->from($control, $type, $constraints)
            : parent::from($control, $type, $constraints);
    }

    /**
     * Create the constraint builder set with default HTML constraints.
     *
     * @return ConstraintBuilder the builder ready to use.
     */
    public static function getDefault()
    {
        $builder = new ConstraintBuilder();

        $numberBuilder = new NumberConstraintBuilder();

        $builder
            // Add standard input
            ->setBuilder(new ColorConstraintBuilder(), 'input', 'color')
            ->setBuilder(new DateConstraintBuilder(), 'input', 'date')
            ->setBuilder(new DateTimeConstraintBuilder(), 'input', 'datetime-local')
            ->setBuilder(new EmailConstraintBuilder(), 'input', 'email')
            ->setBuilder(new MonthConstraintBuilder(), 'input', 'month')
            ->setBuilder($numberBuilder, 'input', 'number')
            ->setBuilder($numberBuilder, 'input', 'range')
            ->setBuilder(new TimeConstraintBuilder(), 'input', 'time')
            ->setBuilder(new UrlConstraintBuilder(), 'input', 'url')
            ->setBuilder(new WeekConstraintBuilder(), 'input', 'week')
            ->setBuilder(new CheckboxConstraintBuilder(), 'input', 'checkbox')
            ->setBuilder(new SelectConstraintBuilder(), 'select')

            // Add widget
            ->setBuilder(new RadioGroupConstraintBuilder(), 'radio-group')
            ->setBuilder(new CheckboxGroupConstraintBuilder(), 'checkbox-group')

            // Add common factories
            ->setFactory('enum', function ($_1, $_2, $enum) {
                // Expect an array of string as enum
                return [self::CSTRT => new Enum($enum)];
            })
            ->setFactory('equals', function ($_1, $_2, $value) {
                // Constraint that check if the value provided is the same $value parameter
                return [self::CSTRT => new Equals($value)];
            })
            ;

        // TODO: input file

        return $builder;
    }

}

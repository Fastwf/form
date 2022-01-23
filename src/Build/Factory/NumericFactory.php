<?php

namespace Fastwf\Form\Build\Factory;

use Fastwf\Form\Build\Factory\DateFactory;
use Fastwf\Form\Build\Factory\TimeFactory;
use Fastwf\Form\Build\Factory\WeekFactory;
use Fastwf\Form\Build\Factory\MonthFactory;
use Fastwf\Form\Build\Factory\NumberFactory;
use Fastwf\Form\Exceptions\FactoryException;
use Fastwf\Form\Build\Factory\DateTimeFactory;

abstract class NumericFactory
{

    /**
     * Create min constraint.
     *
     * @param mixed $min the minimum value.
     * @return array the constraint array
     */
    public abstract function min($min);

    /**
     * Create max constraint.
     *
     * @param mixed $max the maximum value.
     * @return array the constraint array
     */
    public abstract function max($max);

    /**
     * Create step constraint.
     *
     * @param mixed $step the step value
     * @param array $constraints the form control constraints
     * @return array the constraint array
     */
    public abstract function step($step, $constraints);

    /**
     * Return factory corresponding to form control.
     *
     * @param string $control the html control (input, select, ...).
     * @param string|null $type the input type (text, number, ...) or null.
     * @param string $name the constraint name.
     * @return NumericFactory the corresponding factory.
     */
    public static function of($control, $type, $name)
    {
        $factory = null;

        if ($control === 'input')
        {
            switch ($type)
            {
                case 'date':
                    $factory = new DateFactory();
                    break;
                case 'datetime-local':
                    $factory = new DateTimeFactory();
                    break;
                case 'time':
                    $factory = new TimeFactory();
                    break;
                case 'month':
                    $factory = new MonthFactory();
                    break;
                case 'week':
                    $factory = new WeekFactory();
                    break;
                case 'number':
                case 'range':
                    $factory = new NumberFactory();
                    break;
                default:
                    break;
            }
        }

        if ($factory === null)
        {
            throw new FactoryException("Constraint '$name' cannot be used for form control");
        }
        
        return $factory;
    }

}

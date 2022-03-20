<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Parsing\DateParser;
use Fastwf\Form\Parsing\DateTimeParser;
use Fastwf\Form\Parsing\MonthParser;
use Fastwf\Form\Parsing\NumberParser;
use Fastwf\Form\Parsing\TimeParser;
use Fastwf\Form\Parsing\WeekParser;

/**
 * Entity definition for "input" html element.
 * 
 * Use specific child class for input file, radio or checkbox.
 */
class Input extends FormControl
{

    /**
     * The input type.
     *
     * @var string
     */
    protected $type;

    /**
     * {@inheritDoc}
     * 
     * @param array{type:string} $parameters The input parameters that extends {@see FormControl::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->type = ArrayUtil::get($parameters, 'type');
    }

    protected function getDefaultParser($parameters)
    {
        switch (ArrayUtil::getSafe($parameters, 'type'))
        {
            case 'date':
                $parser = new DateParser();
                break;
            case 'datetime':
            case 'datetime-local':
                $parser = new DateTimeParser();
                break;
            case 'month':
                $parser = new MonthParser();
                break;
            case 'number':
            case 'range':
                $parser = new NumberParser();
                break;
            case 'time':
                $parser = new TimeParser();
                break;
            case 'week':
                $parser = new WeekParser();
                break;
            default:
                $parser = parent::getDefaultParser($parameters);
                break;
        }

        return $parser;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTag()
    {
        return 'input';
    }

}

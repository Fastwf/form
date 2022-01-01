<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\FormControl;
use Fastwf\Form\Utils\DateTimeUtil;

class Input extends FormControl
{

    /**
     * The input type.
     *
     * @var string
     */
    protected $type;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->type = ArrayUtil::get($parameters, 'type');
    }

    /**
     * Try to parse the value as an integer according to the step value.
     *
     * @param string|null $value the value to parse
     * @param string $step the step set on input field
     * @return int|double|null
     */
    private function getNumberOf($value, $step)
    {
        // Control nullity of the value
        if ($value === null)
        {
            return null;
        }

        // Evaluate if the field is an integer value or a double
        $matches = [];
        if (\preg_match("/^(\\d+)(?:(\\.\\d+))?$/", (string) $step, $matches) === 1)
        {
            // When the array have 2 elements, the floating point party is empty so it's an integer and not a double
            $isInteger = \count($matches) === 2;
        }
        else {
            $isInteger = true;
        }

        return $isInteger ? (int) $value : (double) $value;
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

    /**
     * The value returned depend on the input type.
     * 
     * Warning: no control are performed so parsing can result in bad value or error (validate the form before).
     *
     * @return mixed
     */
    public function getData()
    {
        switch ($this->type)
        {
            case 'date':
                $data = DateTimeUtil::getDate($this->value, DateTimeUtil::HTML_DATE_FORMAT);
                break;
            case 'number':
            case 'range':
                $data = $this->getNumberOf($this->value, ArrayUtil::getSafe($this->attributes, 'step', '1'));
                break;
            case 'datetime':
            case 'datetime-local':
                $data = DateTimeUtil::getDateTime($this->value, DateTimeUtil::HTML_DATETIME_FORMAT);
                break;
            default:
                $data = $this->value;
                break;
        }

        return $data;
    }

}

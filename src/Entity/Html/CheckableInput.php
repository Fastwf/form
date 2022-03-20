<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Api\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Input;

/**
 * Base class for checkbox and radio inputs.
 */
abstract class CheckableInput extends Input
{

    /**
     * The constant that indicate to synchronize using construct strategy.
     */
    protected const SYNC_CONSTRUCT = 0;

    /**
     * The constant that indicate to synchronize using attribute priority.
     */
    protected const SYNC_ATTRIBUTES = 1;

    /**
     * The constant that indicate to synchronize using value priority.
     */
    protected const SYNC_VALUE = 2;

    /**
     * The constant that indicate to synchronize using check priority.
     */
    protected const SYNC_CHECKED = 3;

    /**
     * True when the input must be checked.
     *
     * @var boolean
     */
    protected $checked;

    /**
     * This value is the value to associate to the checkbox.
     * 
     * ...input value="$valueAttribute"...
     *
     * @var string
     */
    protected $valueAttribute;

    /**
     * {@inheritDoc}
     * 
     * @param array{checked?:boolean} $parameters The CheckableInput parameters that extends {@see Input::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->checked = ArrayUtil::getSafe($parameters, 'checked', false);

        $this->synchronizeValue(self::SYNC_CONSTRUCT);
    }

    public function setAttributes($attributes)
    {
        parent::setAttributes($attributes);

        $this->synchronizeValue(self::SYNC_ATTRIBUTES);
    }

    public function setValue($value)
    {
        parent::setValue($value);

        $this->synchronizeValue(self::SYNC_VALUE);
    }

    public function setChecked($checked)
    {
        $this->checked = $checked;

        $this->synchronizeValue(self::SYNC_CHECKED);
    }

    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * Get the html attribute value of the checkbox.
     *
     * @return string|mixed the value.
     */
    public function getValueAttribute()
    {
        return $this->valueAttribute;
    }

    /**
     * Synchronise internal state to match the real state (correct checked and value properties).
     *
     * @param integer $priority the priority to use for synchronisation.
     * @return void
     */
    protected function synchronizeValue($priority)
    {
        switch ($priority)
        {
            case self::SYNC_VALUE:
                // Set checked when value == attribute value
                $this->checked = $this->value === $this->valueAttribute;
                break;
            case self::SYNC_CHECKED:
                // Set the value as attribute value when it is checked
                $this->value = $this->checked ? $this->valueAttribute : null;
                break;
            case self::SYNC_CONSTRUCT:
            case self::SYNC_ATTRIBUTES:
            default:
                // The content of the value have the priority on checked attribute, check state depend on value equality.
                //  When the value is null, the strategy is based on checked attribute,
                //  else the value is reset to null and checked status to false
                if ($this->value === $this->valueAttribute)
                {
                    $this->checked = true;
                }
                else if ($this->value === null && $this->checked)
                {
                    $this->value = $this->valueAttribute;
                }
                else
                {
                    $this->value = null;
                    $this->checked = false;
                }
                break;
        }
    }

}

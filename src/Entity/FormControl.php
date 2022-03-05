<?php

namespace Fastwf\Form\Entity;

use Fastwf\Form\Entity\Control;
use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Data\Violation;
use Fastwf\Form\Parsing\StringParser;
use Fastwf\Form\Exceptions\ValueError;
use Fastwf\Form\Parsing\ParserInterface;

/**
 * Entity that define common properties of form control.
 */
abstract class FormControl extends Control
{

    /**
     * The value of the form control.
     *
     * @var string
     */
    protected $value;

    /**
     * The constraint to use to validate the value.
     *
     * @var Constraint
     */
    protected $constraint;

    /**
     * The violation to apply to this form control.
     *
     * @var Violation
     */
    protected $violation = [];

    /**
     * The parser to use to stringify or parse form values.
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * {@inheritDoc}
     *
     * @param array{
     *      value?:mixed,
     *      constraint?:Constraint,
     *      violation?:Violation,
     *      parser?:ParserInterface
     * } $parameters the form control parameters that extends {@see Control::__construct} parameters.
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->value = ArrayUtil::getSafe($parameters, 'value');
        $this->constraint = ArrayUtil::getSafe($parameters, 'constraint');
        $this->violation = ArrayUtil::getSafe($parameters, 'violation');

        $parser = ArrayUtil::getSafe($parameters, 'parser');
        $this->parser = $parser === null ? $this->getDefaultParser($parameters) : $parser;
    }

    /**
     * Called to create the default parser when no parser are provided in constructor.
     *
     * @param array $parameters the form control parameters (see the constructor for definition)
     * @return ParserInterface
     */
    protected function getDefaultParser($parameters)
    {
        return new StringParser();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    public function getConstraint()
    {
        return $this->constraint;
    }

    public function setViolation($violation)
    {
        $this->violation = $violation;
    }

    public function getViolation()
    {
        return $this->violation;
    }

    /**
     * Set the new parser for this form control.
     *
     * @param ParserInterface $parser the new parser.
     */
    public function setParser($parser)
    {
        if (!($parser instanceof ParserInterface))
        {
            throw new ValueError("The parser must implements ParserInterface");
        }

        $this->parser = $parser;
    }

    /**
     * Set the parser of this form control.
     *
     * @return ParserInterface $parser the current parser.
     */
    public function getParser()
    {
        return $this->parser;
    }

    public function getData()
    {
        // For compatibility with children classes we use the parser to return the correct data.
        //  By default the form control use StringParser and return the associated string value (or null)
        return $this->parser
            ->parse($this->value, $this);
    }

    /**
     * Get the value as string.
     * 
     * getValue can return a string value or a parsed value, so to be sure to obtain a string use this method.
     *
     * @return string the value string ('' if the value is null)
     */
    public function getSValue()
    {
        return $this->parser
            ->strigify($this->value, $this);
    }

}

<?php

namespace Fastwf\Form\Entity\Html;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Form\Entity\Containers\FormGroup;

/**
 * Entity definition for "form" html element.
 */
class Form extends FormGroup
{

    public const METHOD_GET = 'get';

    public const METHOD_POST = 'post';

    /**
     * The content type of forms to POST with simple input controls.
     * 
     * When file is required use FORM_MULTIPART.
     */
    public const FORM_URL_ENCODED = "application/x-www-form-urlencoded";

    /**
     * The content type of forms to POST when document upload is required (use FILE input controls).
     */
    public const FORM_MULTIPART = "multipart/form-data";

    protected $action;

    protected $method;

    protected $enctype;

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);

        $this->action = ArrayUtil::get($parameters, 'action');
        $this->method = ArrayUtil::getSafe($parameters, 'method', self::METHOD_GET);
        $this->enctype = ArrayUtil::getSafe($parameters, 'enctype', self::FORM_URL_ENCODED);
    }

    public function setAction($action)
    {
        return $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setMethod($method)
    {
        return $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setEnctype($enctype)
    {
        return $this->enctype = $enctype;
    }

    public function getEnctype()
    {
        return $this->enctype;
    }

    /**
     * Validate the value according to the constraint provided.
     *
     * @param Validator|null $validator the validator to use to validate data
     * @param Constraint|null $constraint the constraint to apply to the value
     * @return boolean true when the form is valid
     */
    public function validate($constraint = null, $validator = null)
    {
        if ($constraint === null)
        {
            // Collect constraints from each control nodes
            $constraint = $this->getConstraint();
        }

        // Create/use Validator instance and validate value
        if ($validator === null)
        {
            $validator = new Validator($constraint);
        }
        $isValid = $validator->validate($this->getValue());

        // When validation failed set child errors
        if (!$isValid)
        {
            $this->setViolation($validator->getViolations());
        }

        return $isValid;
    }

    public function getTag()
    {
        return 'form';
    }

}

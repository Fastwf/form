<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Form\Build\ContainerGroupBuilder;
use Fastwf\Form\Build\Security\SecurityPolicy;

/**
 * The builder class pattern that allows to create form entity.
 */
class FormBuilder extends ContainerGroupBuilder
{

    /**
     * The security policy implementation to use to build CSRF tokens.
     *
     * @var ISecurityPolicy
     */
    private $securityPolicy;

    /**
     * The csrf specifications to use to inject CSRF token.
     *
     * @var array
     */
    private $csrfOptions;

    /**
     * {@inheritDoc}
     *
     * @param string $action the action url
     * @param array{
     *      constraintBuilder?:ConstraintBuilder,
     *      name?:string,
     *      attributes:array,
     *      label?:string,
     *      help?:string,
     *      method?:string,
     *      enctype?string,
     *      securityPolicy?:SecurityPolicy
     * } $options The builder parameters (see {@see Form::__construct} parameters).
     */
    public function __construct($action, $options = [])
    {
        parent::__construct(ArrayUtil::getSafe($options, 'name'), $options);

        $options['action'] = $action;
        ArrayUtil::merge($options, $this->parameters, ['action', 'method', 'enctype']);

        $securityPolicy = ArrayUtil::getSafe($options, 'securityPolicy');
        $this->securityPolicy = $securityPolicy === null ? new SecurityPolicy() : $securityPolicy;
    }

    /// OVERRIDE METHODS

    public function addInputFile($name, $options = [])
    {
        parent::addInputFile($name, $options);

        // Reset the enctype to force to have 'multipart/form-data' body encoding.
        $this->parameters['enctype'] = Form::FORM_MULTIPART;

        return $this;
    }

    /// PUBLIC METHODS

    /**
     * Set the form secure or not by injecting an hidden field that hold a CSRF token.
     *
     * @param boolean $secure true to add csrf token protected or false otherwise.
     * @param string $seed the seed to use to generate the CSRF token.
     * @param string|null (in/out) the token to use or to fill and inject in the form.
     * @return $this the current form builder updated.
     */
    public function setSecure($secure, $seed = null, &$token = null, $name = "__token")
    {
        if ($secure)
        {
            if ($token === null)
            {
                // Generate the token
                $token = $this->securityPolicy->newCsrfToken($seed === null ? \random_bytes(8) : $seed);
            }

            $this->csrfOptions = [
                'token' => $token,
                'name' => $name,
            ];
        }
        else
        {
            // Remove all options
            $this->csrfOptions = null;
        }

        return $this;
    }

    /// IMPLEMENTATION

    /**
     * Build the form.
     *
     * @return Form
     */
    public function build()
    {
        // Set the security in form if it's required.
        if ($this->csrfOptions !== null)
        {
            \array_unshift(
                $this->controls,
                new Input([
                    'name' => $this->csrfOptions['name'],
                    'type' => 'hidden',
                    'value' => $this->csrfOptions['token'],
                    'constraint' => new Chain(false, new StringField(), new Equals($this->csrfOptions['token'])),
                ])
            );
        }

        $parameters = $this->parameters;
        $parameters['controls'] = $this->controls;

        return new Form($parameters);
    }

    /// STATIC METHODS

    /**
     * Create an instance of the form builder.
     *
     * @param string $action the action url.
     * @param array $options {@see FormBuilder::__construct} for option details
     * @return FormBuilder the builder generated.
     */
    public static function new($action, $options = [])
    {
        return new FormBuilder($action, $options);
    }

}

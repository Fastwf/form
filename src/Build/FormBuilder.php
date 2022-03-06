<?php

namespace Fastwf\Form\Build;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\StringField;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Form\Build\ContainerGroupBuilder;
use Fastwf\Form\Build\Security\SecurityPolicyInterface;

/**
 * The builder class pattern that allows to create form entity.
 */
class FormBuilder extends ContainerGroupBuilder
{

    /**
     * The id to use to build the form.
     *
     * @var string
     */
    private $id;

    /**
     * The security policy implementation to use to build CSRF tokens.
     *
     * @var SecurityPolicyInterface
     */
    private $securityPolicy;

    /**
     * {@inheritDoc}
     *
     * @param string $id a unique id that represent the form to build.
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
    public function __construct($id, $action, $options = [])
    {
        parent::__construct(ArrayUtil::getSafe($options, 'name'), $options);

        $this->id = $id;

        $options['action'] = $action;
        ArrayUtil::merge($options, $this->parameters, ['action', 'method', 'enctype']);

        $this->securityPolicy = ArrayUtil::getSafe($options, 'securityPolicy');
    }

    /// OVERRIDE METHODS

    public function addInputFile($name, $options = [])
    {
        parent::addInputFile($name, $options);

        // Reset the enctype to force to have 'multipart/form-data' body encoding.
        $this->parameters['enctype'] = Form::FORM_MULTIPART;

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
        // Set the security in form if it's required (securityPolicy provided).
        if ($this->securityPolicy !== null)
        {
            $csrfInput = new Input([
                'name' => $this->securityPolicy->getFieldName(),
                'type' => 'hidden',
                'constraint' => new Chain(
                    false,
                    new StringField(),
                    new Equals($this->securityPolicy->getVerificationCsrfToken($this->id))
                )
            ]);
            $this->securityPolicy->setControl($csrfInput);

            \array_unshift($this->controls, $csrfInput);
        }

        $parameters = $this->parameters;
        $parameters['controls'] = $this->controls;

        return new Form($parameters);
    }

    /// STATIC METHODS

    /**
     * Create an instance of the form builder.
     *
     * @param string $id a unique id that represent the form to build.
     * @param string $action the action url.
     * @param array $options {@see FormBuilder::__construct} for option details
     * @return FormBuilder the builder generated.
     */
    public static function new($id, $action, $options = [])
    {
        return new FormBuilder($id, $action, $options);
    }

}

<?php

namespace Fastwf\Form\Build\Constraints\Widget;

use Fastwf\Form\Utils\ArrayUtil;
use Fastwf\Constraint\Api\Constraint;
use Fastwf\Constraint\Constraints\Chain;
use Fastwf\Form\Constraints\String\Equals;
use Fastwf\Constraint\Constraints\Nullable;
use Fastwf\Constraint\Constraints\Required;
use Fastwf\Constraint\Constraints\String\Enum;
use Fastwf\Constraint\Constraints\Number\Maximum;
use Fastwf\Constraint\Constraints\Number\Minimum;
use Fastwf\Constraint\Constraints\Objects\Schema;
use Fastwf\Constraint\Constraints\String\Pattern;
use Fastwf\Constraint\Constraints\Type\ObjectType;
use Fastwf\Constraint\Constraints\Type\StringType;
use Fastwf\Constraint\Constraints\Type\IntegerType;
use Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder;

/**
 * Builder for input[file] form control.
 */
class FileConstraintBuilder extends FieldMultipleConstraintBuilder
{

    /**
     * The array of extensions accepted for the current input file.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * The array of content types accepted for the current input file.
     *
     * @var array
     */
    protected $contentTypes = [];

    /**
     * An array of number constraint to apply to size file informations.
     *
     * @var array
     */
    protected $size = [];

    public function __construct()
    {
        parent::__construct();

        $this->setFactory('extensions', [$this, 'factoryExtensions'])
            ->setFactory('contentTypes', [$this, 'factoryContentTypes'])
            ->setFactory('minSize', [$this, 'factoryMinSize'])
            ->setFactory('maxSize', [$this, 'factoryMaxSize'])
            ;
    }

    /// PROTECTED METHODS

    /**
     * Generate the name constraint.
     *
     * @return Constraint|null a pattern constraint or null when no extensions are provided.
     */
    protected function getNameConstraint()
    {
        $pattern = null;
        if (!empty($this->extensions))
        {
            $extensions = \implode("|", \array_map(function ($item) { return \substr($item, 1); }, $this->extensions));
            $pattern = [new Pattern("^.+\\.($extensions)$", 'i')];
        }

        return self::buildTypedConstraint(
            new StringType(),
            $pattern,
        );
    }

    /**
     * Generate the type constraint.
     *
     * @return Constraint|null an enum constraint or null when no contentTypes are provided.
     */
    protected function getTypeConstraint()
    {
        return self::buildTypedConstraint(
            new StringType(),
            empty($this->contentTypes) ? null : [new Enum($this->contentTypes)],
        );
    }

    /**
     * Generate the size constraint.
     *
     * @return Constraint|null a chain constraint that control that the value is an integer and add optionnaly min and max constraints.
     */
    protected function getSizeConstraint()
    {
        $constraints = [];

        // Add the min constraint if required
        if (\array_key_exists('min', $this->size))
        {
            \array_push($constraints, new Minimum($this->size['min']));
        }
        
        // Add the max constraint if required
        if (\array_key_exists('max', $this->size))
        {
            \array_push($constraints, new Maximum($this->size['max']));
        }

        return self::buildTypedConstraint(new IntegerType(), $constraints);
    }

    /// PUBLIC METHODS

    /**
     * The factory callback to use that allows to set the accepted extensions.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $extensions the list of extensions accepted (must contains the "." before the extension chars like "accept" HTML
     *                            attribute).
     * @return array
     */
    public function factoryExtensions($_1, $_2, $extensions)
    {
        $this->extensions = $extensions;

        // Return an empty because the constraint is applyed to object property name
        return [];
    }

    /**
     * The factory callback to use that allows to set the accepted extensions.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $contentTypes the list of content types accepted (must respect the "accept" HTML attribute specifications).
     * @return array
     */
    public function factoryContentTypes($_1, $_2, $contentTypes)
    {
        $this->contentTypes = $contentTypes;

        // Return an empty because the constraint is applyed to object property name
        return [];
    }

    /**
     * The factory callback to use that allows to apply min constraint on uploaded file size.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $size the minimum file size allowed.
     * @return array set HTML attributes accessible by javascript for frontend validation (el.dataset.minSize).
     */
    public function factoryMinSize($_1, $_2, $size)
    {
        $this->size['min'] = $size;

        return [self::ATTRS => ['data-min-size' => $size]];
    }

    /**
     * The factory callback to use that allows to apply max constraint on uploaded file size.
     *
     * @param string $_1 (ignored) the control.
     * @param string|null $_2 (ignored) the control type.
     * @param boolean $size the maximum file size allowed (max included).
     * @return array set HTML attributes accessible by javascript for frontend validation (el.dataset.maxSize).
     */
    public function factoryMaxSize($_1, $_2, $size)
    {
        $this->size['max'] = $size;

        return [self::ATTRS => ['data-max-size' => $size]];
    }

    /// OVERRIDE METHODS

    protected function resetFrom($control, $type, $constraints)
    {
        parent::resetFrom($control, $type, $constraints);

        // Reset the file constraints property
        $this->extensions = [];
        $this->contentTypes = [];
        $this->size = [];
    }

    protected function buildEntryConstraint()
    {
        // Update html attributes to inject extensions and accepted content types
        //  While working $this->htmlAttributes['accept'] when is set is considered as an array
        $accept = \array_unique(
            \array_merge(
                ArrayUtil::getSafe($this->htmlAttributes, 'accept', []),
                $this->extensions,
                $this->contentTypes,
            ),
        );
        // Add the accept html attribute when there is at least 1 accept item
        if ($accept) {
            $this->htmlAttributes['accept'] = \implode(',', $accept);
        }

        // Create the entry constraint
        $options = [
            'properties' => [
                'name' => self::buildPropertyConstraint($this->getNameConstraint()),
                'type' => self::buildPropertyConstraint($this->getTypeConstraint()),
                'size' => self::buildPropertyConstraint($this->getSizeConstraint()),
                // For 'path', the property is required and must not be null
                'path' => self::buildPropertyConstraint(null),
                // For 'error', the property must be 0 (UPLOAD_ERR_OK), if it's null or any other error const, the validation failed
                'error' => new Equals(\UPLOAD_ERR_OK),
            ],
        ];

        // Do not use the default constraints because it's required to validate an object
        $chainedConstraints = new Chain(
            true,
            new ObjectType(),
            new Schema($options),
        );

        return new Nullable(!$this->required, $chainedConstraints);
    }

    // STATIC METHODS

    /**
     * Produce a required and not nullable property that respect the $constraint.
     *
     * @param Constraint|null $constraint the constraint to use or null when no validation are required for the property.
     * @return Constraint the property constraint ready to use.
     */
    protected static function buildPropertyConstraint($constraint)
    {
        $nullable = new Nullable(false, $constraint);
        return new Required(true, $nullable);
    }

    /**
     * Build a constraint with non safe $typeConstraint control before calling $safeConstraint.
     *
     * @param Constraint $typeConstraint the type constraint to use as non safe constraint.
     * @param array|null $safeConstraints the next safe constraints to use after type check, or null if only type check is required.
     * @return Constraint the constraint built or $typeConstraint when only this is required.
     */
    protected static function buildTypedConstraint($typeConstraint, $safeConstraints)
    {
        if (!empty($safeConstraints))
        {
            $typeConstraint = new Chain(
                true,
                $typeConstraint, // integer type
                self::chainConstraints($safeConstraints, false),
            );
        }

        return $typeConstraint;
    }

}

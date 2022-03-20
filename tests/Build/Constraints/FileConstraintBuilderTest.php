<?php

namespace Fastwf\Tests\Build\Constraints;

use PHPUnit\Framework\TestCase;
use Fastwf\Api\Model\UploadedFile;
use Fastwf\Constraint\Api\Validator;
use Fastwf\Form\Build\Constraints\AConstraintBuilder;
use Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder;

class FileConstraintBuilderTest extends TestCase
{

    /**
     * The builder to use for tests
     *
     * @var FileConstraintBuilder
     */
    private $builder;

    protected function setUp(): void {
        $this->builder = new FileConstraintBuilder();
    }

    /**
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Constraints\String\Equals
     */
    public function testStandardConstraints()
    {
        $params = $this->builder->from('input', 'file', [])
            ->build();
        
        $this->assertTrue(
            (new Validator($params[AConstraintBuilder::CSTRT]))
                ->validate(new UploadedFile([
                    'name' => 'schema.svg',
                    'type' => 'image/svg+xml',
                    'size' => 4724,
                    'tmp_name' => '/tmp/randomSequence',
                    'error' => \UPLOAD_ERR_OK,
                ])),
        );
    }

    /**
     * @covers Fastwf\Form\Build\Constraints\Widget\FileConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\AConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\AOptionMultipleConstraintBuilder
     * @covers Fastwf\Form\Build\Constraints\Widget\FieldMultipleConstraintBuilder
     * @covers Fastwf\Form\Constraints\String\Equals
     */
    public function testOnlyPngFiles()
    {
        $params = $this->builder->from('input', 'file', [])
            // accept .png files
            ->add('extensions', ['.png'], [])
            ->add('contentTypes', ['image/png'], [])
            // non empty file
            ->add('minSize', 1, [])
            // < max upload size file accepted
            ->add('maxSize', 8 * (1024 ** 2), [])
            ->build();
        
        $this->assertEquals('.png,image/png', $params[AConstraintBuilder::ATTRS]['accept']);
        // Test an invalid uploaded file
        $this->assertFalse(
            (new Validator($params[AConstraintBuilder::CSTRT]))
                ->validate(new UploadedFile([
                    'name' => 'schema.svg',
                    'type' => 'image/svg+xml',
                    'size' => 0,
                    'tmp_name' => '/tmp/randomSequence',
                    'error' => \UPLOAD_ERR_INI_SIZE,
                ])),
        );
        // Test a valid uploaded file
        $this->assertTrue(
            (new Validator($params[AConstraintBuilder::CSTRT]))
                ->validate(new UploadedFile([
                    'name' => 'schema.png',
                    'type' => 'image/png',
                    'size' => 4724,
                    'tmp_name' => '/tmp/randomSequence',
                    'error' => \UPLOAD_ERR_OK,
                ])),
        );
    }

}

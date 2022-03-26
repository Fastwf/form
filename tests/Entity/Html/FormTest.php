<?php

namespace Fastwf\Tests\Entity\Html;

use PHPUnit\Framework\TestCase;
use Fastwf\Form\Entity\Html\Form;
use Fastwf\Form\Entity\Html\Input;
use Fastwf\Constraint\Constraints\String\NotBlank;
use Fastwf\Constraint\Constraints\String\EmailFormat;

class FormTest extends TestCase
{

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input  
     */
    public function testSetValue()
    {
        $form = new Form([
            'action' => '',
            'controls' => [
                new Input(['type' => 'text', 'name' => 'username']),
                new Input(['type' => 'email', 'name' => 'email']),
            ],
        ]);

        $form->setValue([
            'username' => 'foo',
            'email' => 'foo@bar.com',
        ]);

        $this->assertEquals('foo', $form->findControl('username')->getValue());
        $this->assertEquals('foo@bar.com', $form->findControl('email')->getValue());
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     */
    public function testGetTag()
    {
        $form = new Form(['action' => '', 'controls' => []]);

        $this->assertEquals('form', $form->getTag());
    }

    /**  
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     * @covers Fastwf\Form\Entity\Html\Input  
     */
    public function testValidateNoDataConvertion()
    {
        $form = new Form([
            'action' => '',
            'controls' => [
                new Input(['type' => 'text', 'name' => 'username', 'constraint' => new NotBlank()]),
                new Input(['type' => 'email', 'name' => 'email', 'constraint' => new EmailFormat()]),
            ],
        ]);

        $form->setValue([
            'username' => 'foo',
            'email' => 'foo@bar.com',
        ]);
        $this->assertTrue($form->validate());

        $form->setValue([
            'username' => '  ',
            'email' => 'foo',
        ]);
        $this->assertFalse($form->validate());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     */
    public function testSetGetAction()
    {
        $form = new Form(['action' => '', 'controls' => []]);
        $this->assertEquals('', $form->getAction());

        $form->setAction('/foo/bar');
        $this->assertEquals('/foo/bar', $form->getAction());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     */
    public function testSetGetMethod()
    {
        $form = new Form(['action' => '', 'controls' => []]);
        $this->assertEquals('get', $form->getMethod());

        $form->setMethod(Form::METHOD_POST);
        $this->assertEquals(Form::METHOD_POST, $form->getMethod());
    }

    /**
     * @covers Fastwf\Form\Entity\Control
     * @covers Fastwf\Form\Entity\FormControl
     * @covers Fastwf\Form\Entity\Containers\FormGroup
     * @covers Fastwf\Form\Entity\Containers\AFormGroup
     * @covers Fastwf\Form\Entity\Html\Form
     */
    public function testSetGetEnctype()
    {
        $form = new Form(['action' => '', 'controls' => []]);
        $this->assertEquals(Form::FORM_URL_ENCODED, $form->getEnctype());

        $form->setEnctype(Form::FORM_MULTIPART);
        $this->assertEquals(Form::FORM_MULTIPART, $form->getEnctype());
    }

}

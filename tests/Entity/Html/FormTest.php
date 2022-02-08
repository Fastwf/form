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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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
     * @covers Fastwf\Form\Utils\ArrayUtil
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

}

<?php

namespace Fastwf\Form\Build\Groups;

use Fastwf\Form\Build\Groups\ArrayBuilder;
use Fastwf\Form\Build\Groups\GroupBuilder;

/**
 * Use this interface to force user to call mandatory methods.
 */
interface IArrayBuilder
{

    /**
     * Use an input form control that respect the specifications as array control.
     * 
     * For 'radio' and 'checkbox' type use their specific method.
     * 
     * @param string $type the input type.
     * @param array $options the array of textarea options.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofInput($type = 'text', $options = []);

    /**
     * Use a textarea form control that respect the specifications as array control.
     *
     * @param array $options the array of textarea options.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofTextarea($options);

    /**
     * Use a select form control that respect the specifications as array control.
     *
     * @param array $options the array of select specifications.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofSelect($options);

    /**
     * Use an input checkbox form control that respect the specifications as array control.
     *
     * @param array $options the array of checkbox options.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofCheckbox($options);

    /**
     * Use an input file form control that respect the specifications as array control.
     *
     * @param array $options the array of checkbox options.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofInputFile($options);

    /**
     * Use a checkbox group using choices to build each input checkbox as array control.
     *
     * @param array $options the array of checkbox group specifications.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofCheckboxGroup($options);

    /**
     * Use a radio group using choices to build each input radio as array control.
     *
     * @param array $options the array of radio group specifications.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofRadioGroup($options);

    /**
     * Use a button html entity set with options as array control.
     *
     * @param string $type the button type.
     * @param array $options the button options.
     * @return ArrayBuilder the array builder with control set.
     */
    public function ofButton($type = Button::TYPE_SUBMIT, $options = []);

    /**
     * Create a new group builder to set the array control as FormGroup.
     *
     * @return GroupBuilder the sub builder to use to create the control.
     */
    public function ofGroup();

    /**
     * Create a new array builder to set the array control as FormArray.
     *
     * @return IArrayBuilder the sub builder to use to create the control.
     */
    public function ofArray();

}

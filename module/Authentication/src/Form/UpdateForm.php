<?php

namespace Authentication\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class UpdateForm extends Form
{
    public function __construct()
    {
        parent::__construct('update-form');

        $this->setAttributes([
            'class' => 'form-horizontal',
        ]);

        $this->createElements();
    }

    protected function createElements()
    {
        $firstName = new Element\Text('firstName');
        $firstName->setLabel('First name:');
        $firstName->setLabelAttributes([
            'class' => 'label-control',
        ]);
        $firstName->setAttributes([
            'class'    => 'form-control',
            'required' => 'required',
            'id'       => 'firstName',
        ]);
        $firstName->setOptions([
            'min' => 2,
            'max' => 50,
        ]);
        $this->add($firstName);

        $lastName = new Element\Text('lastName');
        $lastName->setLabel('Last name:');
        $lastName->setLabelAttributes([
            'class' => 'label-control',
        ]);
        $lastName->setAttributes([
            'class'    => 'form-control',
            'required' => 'required',
            'id'       => 'lastName',
        ]);
        $lastName->setOptions([
            'min' => 2,
            'max' => 50,
        ]);
        $this->add($lastName);

        $this->add([
            'name' => 'password',
            'type' => 'password',
            'attributes' => [
                'class'    => 'form-control',
                'id'       => 'password',
            ],
            'options' => [
                'label' => 'Change Password:',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'min' => 2,
                'max' => 100,
            ],
        ]);

        $location = new Element\Text('location');
        $location->setLabel('Location:');
        $location->setLabelAttributes([
            'class' => 'label-control',
        ]);
        $location->setAttributes([
            'class'    => 'form-control',
            'required' => 'required',
            'id'       => 'location',
        ]);
        $location->setOptions([
            'min' => 2,
            'max' => 50,
        ]);
        $this->add($location);

        $role = new Element\Select('role');
        $role->setLabel('Role:');
        $role->setLabelAttributes([
            'class' => 'control-label',
        ]);
        $role->setAttributes([
            'class' => 'form-control',
            'id'    => 'role',
        ]);
        $role->setOptions([
            'min' => 4,
            'max' => 5,
            'value_options' => [
                'user'  => 'user',
                'admin' => 'admin',
            ],
        ]);
        $this->add($role);

        $file = new Element\File('file');
        $file->setLabel('Change avatar:');
        $role->setLabelAttributes([
            'class' => 'control-label',
        ]);
        $file->setAttributes([
            'class' => 'form-control jfilestyle',
            'id'    => 'file',
        ]);
        $this->add($file);

        $submit = new Element\Submit('submit');
        $submit->setAttributes([
            'class' => 'btn btn-default',
            'value' => 'Edit',
            'id'    => 'submit',
        ]);
        $this->add($submit);
    }
}

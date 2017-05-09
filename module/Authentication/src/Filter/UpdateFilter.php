<?php

namespace Authentication\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

class UpdateFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name'       => 'firstName',
            'required'   => true,
            'filters'    => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 2,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'lastName',
            'required'   => true,
            'filters'    => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 2,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => false,
            'allowEmpty' => true,
            'filters' => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 2,
                        'max' => 100,
                    ],
                ]
            ],
        ]);

        $this->add([
            'name'       => 'location',
            'required'   => true,
            'filters'    => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 2,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'role',
            'filters'    => [
                ['name' => 'stripTags'],
                ['name' => 'stringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'stringLength',
                    'options' => [
                        'encoding' => 'utf-8',
                        'min' => 4,
                        'max' => 5,
                    ],
                ],
            ],
        ]);

        $this->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => false,
            'allowEmpty' => true,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                ['name'    => 'FileIsImage'],
                [
                    'name' => 'Zend\Validator\File\Extension',
                    'options' => [
                        'extension' => ['png', 'jpg', 'jpeg', 'gif'],
                    ],
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'            =>'./public_html/img/user/',
                        'useUploadName'     =>true,
                        'useUploadExtension'=>true,
                        'overwrite'         =>true,
                        'randomize'         =>false
                    ]
                ]
            ],
        ]);
    }
}

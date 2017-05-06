<?php

namespace Authentication\Filter;

use Zend\InputFilter\InputFilter;

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
    }
}

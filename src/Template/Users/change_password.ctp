<?php
$params = [
    'form' => [
        'options' => [
            'type' => 'post',
            'novalidate' => true,
            'id' => 'editRealtorForm',
            'url' => ['controller' => 'Users', 'action' => 'changePassword']
        ],
        'heading' => 'Profile Information',
    ],
    'fields' => [
        [
            'name' => 'password',
            'type' => 'password',
            'validate' => [
                'rules' => [
                    'required' => true,
                    'pwcheck' => true
                ]
            ]
        ],
        ['name' => 'empty'],
        [
            'name' => 'confirm_password',
            'type' => 'password',
            'validate' => [
                'rules' => [
                    'required' => true,
                    'equalTo' => '#Password'
                ]
            ]
        ],
        ['name' => 'empty'],
    
    ]
];
$this->CustomForm->create($params);

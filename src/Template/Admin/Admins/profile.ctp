<?php
$params = [
    'form' => [
        'options' => [
            'type' => 'post',
            'novalidate' => true,
            'id' => 'adminProfileForm'
        ],
        'heading' => 'Profile'
    ],
    'fields' => [
        [
            'name' => 'image_id',
            'type' => 'image',
            'columns' => 12,
            'model' => 'Admins',
            'category' => 'Admin Profile'
        ],
        ['name' => 'name'],
        [
            'name' => 'email',
            'readonly' => true
        ],
    ]
];

$changePassParams = [
    'form' => [
        'options' => [
            'type' => 'post',
            'novalidate' => true,
            'id' => 'changePasswordAdminsForm',
            'url' => ['controller' => 'Admins', 'action' => 'changePassword']
        ],
        'heading' => 'Change Password',
        'cancel' => false,
    ],
    'fields' => [
        [
            'name' => 'current_password',
            'type' => 'password',
            'columns' => 12
        ],
        [
            'name' => 'new_password',
            'type' => 'password',
            'id' => 'adminPassword',
            'columns' => 12,
            'validate' => [
                'rules' => [
                    'required' => true,
                    'pwcheck' => true,
                    'minlength' => 8
                ]
            ]
        ],
        [
            'name' => 'confirm_password',
            'type' => 'password',
            'columns' => 12,
            'validate' => [
                'rules' => [
                    'required' => true,
                    'equalTo' => "#adminPassword"
                ]
            ]
        ]
    ],
    'submit' => [
        'label' => 'Change Password'
    ]
];

?>
<div class="row">
    <div class="col-md-8">
        <?php $this->AdminForm->create($params); ?>
    </div>
    <div class="col-md-4">
        <?php $this->AdminForm->create($changePassParams); ?>
    </div>
</div>


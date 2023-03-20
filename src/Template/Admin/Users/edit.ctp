<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$params = [
    'form'     => [
        'options' => [
            'type'       => 'post',
            'novalidate' => true,
            'id'         => 'editUserForm'
        ],
        'heading' => 'Edit Users'
    ],
    'fields'   => [
        [
            'name'     => 'email',
            'validate' => [
                'rules' => [
                    'required' => true,
                    'email'    => true,
                    'remote'   => SITE_URL . 'admin/admins/isUniqueEmail/'.$user->id,
                ]
            ]
        ],
    ],
];

$this->AdminForm->create($params);

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
            'id'         => 'editStickerForm'
        ],
        'heading' => 'Edit Reward'
    ],
    'fields'   => [
        [
            'name' => 'image_id',
            'type' => 'image',
            'columns' => 6,
            'model' => 'Users',
            'category' => 'Profile'
        ],
        ['name'=>'empty'],
        [
            'name'     => 'name',
            'label'     => 'Title',
            'columns' => 6,
        ],
        ['name'=>'empty'],
        [
            'name'     => 'description',
            'type'     => 'textarea',
            'columns' => 6,
        ],
        ['name'=>'empty'],
        [
            'name'     => 'no_of_affiliate_required',
            'label'     => 'No. of affiliates required to achieve',
            'columns' => 6,
        ],
    ],
];

$this->AdminForm->create($params);

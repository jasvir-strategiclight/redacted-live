<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->Heading->create('Rewards');

$params = [
    'fields' => [
        [
            'name'     => 'image_id',
            'type'     => 'image',
            'sortable' => false,
            'label'    => '<i class="fa fa-picture-o"></i>'
        ],
        [
            'name'  => 'name',
            'label' => 'Title',

        ],
        [
            'name'  => 'no_of_affiliate_required',
            'label' => 'No. of affiliates required to achieve',
        ],
        [
            'name'  => 'status',
            'label' => 'Status',
            'type'  => 'status',
            'model' => 'Rewards',
        ]
    ],
    'search' => [
        'match' => [
            'Users' => ['email']
        ]
    ]
];

$this->AdminListing->create($params, ['Edit', 'Delete']);



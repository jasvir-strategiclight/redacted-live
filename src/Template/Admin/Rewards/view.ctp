<?php
$params = [
    'fields' => [
        [
            'name'     => 'image_id',
            'type'     => 'image',
            'sortable' => false,
            'label'    => 'Sticker Image <i class="fa fa-picture-o"></i>:'
        ],
        [
            'name'  => 'name',
            'label' => 'Title',

        ],
        [
            'name'  => 'description'

        ],
        [
            'name'  => 'no_of_affiliate_required',
            'label' => 'No. of affiliates required to achieve',
        ],
        [
            'name'  => 'active',
            'label' => 'Status',
            'type'  => 'status',
            'model' => 'Stickers',
        ]
    ],
    'controller' => 'Apartments',
];
$this->AdminDetail->info($params, "Sticker Information");



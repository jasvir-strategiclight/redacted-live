<?= $this->Html->script('ckeditor/ckeditor'); ?>
    <style>
        [aria-labelledby] {
            opacity: 1 !important;
        }
    </style>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$params = [
    'form' => [
        'options' => [
            'type' => 'post',
            'novalidate' => true,
            'id' => 'addPageForm'
        ],
        'heading' => 'New Page Section'
    ],
    'fields' => [
        [
            'name' => 'page',
            'columns' => 12,
        ],
        [
            'name' => 'heading',
            'columns' => 12,
        ],
        [
            'name' => 'content',
            'type' => 'textarea',
            'columns' => 12,
            'classes' => 'ckeditor'
        ],
//        [
//            'name' => 'menu_assigned',
//            'type' => 'select',
//            'options' => ['Header' => 'Header', 'Footer' => 'Footer', 'Both' => 'Both', 'None' => 'None'],
//            'columns' => 6,
//        ],
//        [
//            'name' => 'menu_item_order',
//            'type' => 'number',
//            'columns' => 6,
//        ],
    ]
];

$this->AdminForm->create($params);


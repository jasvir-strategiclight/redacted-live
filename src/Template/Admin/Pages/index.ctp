<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]|\Cake\Collection\CollectionInterface $pages
 */
$this->Heading->create('Pages');

$params =['fields' => [
    ['name' => 'page'],
    ['name' => 'heading'],
    [
        'name' => 'modified',
        'label' => 'Last Updated'
    ],
//    ['name' => 'menu_assigned'],
//    ['name' => 'menu_item_order'],
    //['name' => 'content'],
    ],
    'search'=>[
        'match'=>[
            'Pages'=>['heading', 'content']
        ]
    ]
];

$this->AdminListing->create($params, ['edit']);
?>
<script>
    $(function () {
        $('#topBreadcrumb').hide();
    });
</script>

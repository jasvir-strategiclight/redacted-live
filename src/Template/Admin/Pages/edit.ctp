<?= $this->Html->script('ckeditor/ckeditor'); ?>
<style>
    [aria-labelledby]{opacity: 1 !important;}
</style>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
switch($page->page){
    case "Home":{
        $fields = [
            [
                'name' => 'page',
                'columns' => 12,
                'readonly' => true
            ],
            [
                'name' => 'image_id',
                'type' => 'image',
                'columns' => 12,
            ],
            [
                'name' => 'content',
                'type' => 'textarea',
                'columns' => 12,
                'classes' => 'ckeditor'
            ]

        ];
        break;
    }
    
    case "Plans":{
        $plans = json_decode($page->plans);
        $fields = [
            [
                'name' => 'page',
                'columns' => 12,
                'readonly' => true
            ],
            [
                'name' => 'image_id',
                'type' => 'image',
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
            [
                'name' => 'test_plan',
                'columns' => 12,
                'value' => $plans->test_plan,
            ],
            [
                'name' => 'live_plan',
                'columns' => 12,
                'value' => $plans->live_plan,
            ],
        
        ];
        break;
    }
    
    default :{
        $fields =[
            [
                'name' => 'page',
                'columns' => 12,
                'readonly' => true
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
            //        ]
        ];
    }
}
$params = [
    'form' => [
        'options' => [
            'type' => 'post',
            'novalidate' => true,
            'id' => 'addPageForm'
        ],
        'heading' => 'Edit Page'
    ],
    'fields' => $fields
];

$this->AdminForm->create($params);
?>
<script>
    $(function () {
       $('#topBreadcrumb').hide();
    });
</script>

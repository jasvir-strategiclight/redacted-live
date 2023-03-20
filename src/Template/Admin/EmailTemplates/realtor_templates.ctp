<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\City[]|\Cake\Collection\CollectionInterface $cities
 */
$this->Heading->create('Realtor Email Templates');

$params = [
    'fields' => [
        ['name' => 'label'],
        ['name' => 'subject'],
        ['name' => 'created'],
    ],
    'search' => [
        'match' => [
            'EmailTemplates' => ['label', 'subject', 'created']
        ]
    ]
];

$this->AdminListing->create($params, [
    [
        'label' => 'Preview',
        'url'   => ['controller' => 'EmailTemplates', 'action' => 'preview'],
        'id'    => true,
        'class' => 'btn btn-success btn-sm view-preview',
        'icon'  => 'fa fa-eye'
    ],
    'edit',
]);
?>
<script>
    $(function () {
        $('#topBreadcrumb').hide();
        $('.view-preview').click(function (e) {
            e.preventDefault();

            var newModal = new Custombox.modal({
                content: {
                    target: '#emailPreviewModal',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                }
            });

            newModal.open();

            $.get($(this).attr('href'), function (data) {
                $("#emailTemplatePreviewInPopup").html(data);
            });

        });

    });
</script>
<style>
    [aria-labelledby] {
        opacity: 1 !important;
    }

    #topBreadcrumb {
        display: none;
    }
</style>
<div id="emailPreviewModal" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="max-height: 800px; max-width: 1200px; display: none; width: 80%;  min-width: 1200px;    min-height: 500px; ">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Preview</h4>
    <div calss="modal-body" id="emailTemplatePreviewInPopup" style="position: relative;"></div>
    <div class="clear-both"></div>
</div>
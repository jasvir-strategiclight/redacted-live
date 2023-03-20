
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\City[]|\Cake\Collection\CollectionInterface $cities
 */
$this->Heading->create('Email Templates');

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
        'class' => 'btn-u btn-u-sea btn-u-sm rounded view-preview',
        'icon'  => 'fa fa-eye'
    ],
    'edit',
    'neweditor',
    'delete',
    'reset',
    'Post',
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

            $("#emailTemplatePreviewInPopup").html("");

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


<script type="text/javascript">
    $(document).ready(function(){
        

        
//$('#myModal').modal('show');
    
  
 $('.edit').click(function (e) {
   // alert('ok')
   
    editBtnId = $(this).attr('data-temp_id');
   //alert(editBtnId);
                      
  $.ajax({
        type: "GET",
        url: SITE_URL + 'admin/emailTemplates/chkEdit/'+ editBtnId,
        dataType: "json",
        success: function (resp) {
             console.log(resp.admin);
             //die();
            if(resp.is_open == 1){
            $('#admin_name').text(resp.admin.admin.name +' Editing this template.');
            $('#myModal').modal('show'); 
            //e.preventDefault();  
            }else{
                 window.location.href = "<?= SITE_URL.'admin/email_templates/edit/' ?>"+editBtnId;    
            }
        }
        
        });
 
  
  });



 $('#over_take').click(function (e) {
    window.location.href = "<?= SITE_URL.'admin/email_templates/edit/' ?>"+editBtnId+'?overtake=1'; 
    //e.preventDefault();                           
 });


});

 


</script>



 
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Alert</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <div class="modal-body">
                    <center id=admin_name></center>
                    <p>You already have open tab so please close tab otherwise data will be overwritten.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="over_take">Over Take</button>
                </div>
            </div>
        </div>
    </div>


<div id="emailPreviewModal" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="max-height: 800px; max-width: 1200px; display: none; width: 80%;  min-width: 1200px;    min-height: 500px; ">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Preview</h4>
    <div calss="modal-body" id="emailTemplatePreviewInPopup" style="position: relative;"></div>
    <div class="clear-both"></div>
</div>


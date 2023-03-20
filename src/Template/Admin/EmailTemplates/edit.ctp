<?= $this->Html->script('ckeditor/ckeditor'); ?>

<?php
$params = [
    'form'   => [
        'options' => [
            'type'       => 'post',
            'novalidate' => true,
            'id'         => 'EmailTemplateForm'
        ],
        'heading' => 'Edit Email Template'
    ],
    'fields' => [
        [
            'name'  => 'id',
            'value' => $emailTemplate->id,
            'type'  => 'hidden',
        ],
         [
            'name'    => 'label',
            'columns' => 12
        ],
        ['name' => 'empty'],
        [
            'name'    => 'subject',
            'columns' => 12
        ],
        ['name' => 'empty'],
        [
            'name'    => 'preview_line',
            'columns' => 12
        ],
        ['name' => 'empty'],
        [
            'name'     => 'newsletter_url',
            'columns'  => 12,
            'validate' => [
                'rules' => [
                    'url' => true
                ]
            ]
        ],
        ['name' => 'empty'],
        [
            'name'    => 'template',
            'type'    => 'textarea',
            'class'   => 'ckeditor',
            'columns' => 12
        ],
        ['name' => 'empty'],
    ]
];
?>
<?php
if ($emailTemplate->category == "Client List Default") {
    $params['fields'][] = [
        'name'  => 'go_to',
        'type'  => 'hidden',
        'value' => 'realtorTemplates',
    ];
}
?>
    <style>
        [aria-labelledby] {
            opacity: 1 !important;
        }

        #topBreadcrumb {
            display: none;
        }
    </style>
    <div class="row mt-4">
        <div class="col-md-1">&nbsp;</div>
        
        <div class="col-md-10">
             </div> <?php $this->AdminForm->create($params); ?> </div>
        <div class="col-md-1">&nbsp;</div>
    </div>


    <script>
        //alert($("input[name=id]").val());
        var subject = "";
        var chkinterval;
        $(function () {
                // for (var i in CKEDITOR.instances) {
                //     CKEDITOR.instances[i].on('blur', function (e) {
                //         $('#' + this.name + "Preview").html(this.getData());
                //     });
                // }

                $('#topBreadcrumb').hide();

                CKEDITOR.config.placeholder_select = {
                    placeholders: <?= json_encode(["REFERRAL_URL","VERIFY_URL"]) ?>,
                    format: '[%placeholder%]'
                };

               
                setInterval(function () {
                    
                    
                    for (var i in CKEDITOR.instances) {
                        $('#Template').val(CKEDITOR.instances[i].getData());
                    }

                    $.ajax({
                        type: "POST",
                        url: SITE_URL + 'admin/emailTemplates/save',
                        data: $('#EmailTemplateForm').serialize(),
                        dataType: "json",
                        success: function (resp) {
                            $('#Id').val(resp.id);
                            console.log(resp);
                            if(resp.is_save == 2){
                                //alert('ookok');
                                $('#admin_name').text(resp.admin.admin.name +' Editing this template.');
                                $('#myModal').modal('show'); 
                            }
                            //Do something -here
                        }
                    });

                }, 10000);
                

            
            

              
                $('#Subject').focus(function () {
                    if (subject.length <= 0) {
                        subject = $(this).val();
                    }
                });
  
                $('#Subject').keyup(function () {
                    setNewsLetterUrl();
                }).keydown(function () {
                    setNewsLetterUrl();
                });

                function setNewsLetterUrl() {
                    //https://www.morninginvest.com/newsletter/92-million-voted-already-november-02-2020/
                    //https://www.morninginvest.com/newsletter/62-days-for-december-24-2020
                    var basicUrl = "https://www.redacted.inc/newsletter/slug-<?= strtolower(date("F-d-Y")); ?>";

                    var newSubject = $('#Subject').val();


                    if (subject != newSubject) {
                        var slug1 = newSubject.replace(/'/g, '');
                       // var slug = newSubject.toLowerCase().replace(/[^\w-]+/g, ' ');
                       var slug = slug1.toLowerCase().replace(/[^\w-]+/g, ' ');
                        slug = $.trim(slug);
                        slug = slug.replace(/ /g, '-')
                        basicUrl = basicUrl.replace("slug", slug);

                        $('#NewsletterUrl').val(basicUrl);
                    }

                }

                $('#PreviewLine').focus(function () {
            if (subject.length <= 0) {
                subject = $(this).val();
            }
        });

        $('#PreviewLine').focus(function () {
            if (subject.length <= 0) {
                subject = $(this).val();
            }
        });

        $('#PreviewLine').keyup(function () {
            setNewsLetterUrl2();
        }).keydown(function () {
            setNewsLetterUrl2();
        });

        function setNewsLetterUrl2() {
            var basicUrl = "<?= NEWSLETTER_URL.strtolower(date("F-d-Y")); ?>";

            var newSubject = $('#Subject').val();
            var slug = newSubject.toLowerCase().replace(/[^\w-]+/g, ' ');
            slug = $.trim(slug);
            slug = slug.replace(/ /g, '-')
            basicUrl = basicUrl.replace("slug", slug);
            $('#NewsletterUrl').val(basicUrl);

        }




              

             
            }
        );
    </script>

    <script>
        
   $(document).ready(function(){  
    $('.over_take').click(function (e) {
        //alert('oko');
        window.location.href = "<?= SITE_URL.'admin/email_templates/edit/' ?>"+$("input[name=id]").val()+'?overtake=1'; 
        //e.preventDefault();                           
     });




});


window.addEventListener('beforeunload', function (e) {
    $.ajax({
        type: "GET",
        url: SITE_URL + 'admin/emailTemplates/is_open/'+ $("input[name=id]").val(),
        dataType: "json",
        success: function (resp) {
                                    }
        });

});
</script>

    <!-- Modal HTML-->
    <div id="myModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Alert</h5>
                  
                </div>
                <div class="modal-body">
                    <center id=admin_name></center>
                    <p>You already have open tab so please close tab otherwise data will be overwritten.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary over_take" >Over Take</button>
                </div>
            </div>
        </div>
    </div>
    
<?= $this->element('media') ?>
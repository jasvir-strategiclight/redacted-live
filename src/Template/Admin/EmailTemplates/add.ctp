<?= $this->Html->script('ckeditor/ckeditor'); ?>
<?php
$params = [
    'form'   => [
        'options' => [
            'type'       => 'post',
            'novalidate' => true,
            'id'         => 'EmailTemplateForm'
        ],
        'heading' => 'Add New Email Template'
    ],
    'fields' => [
        [
            'name'  => 'id',
            'value' => 0,
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
        [
            'name'  => 'placeholders',
            'type'  => 'hidden',
            'value' => 'EMAIL,PASSWORD,URL,NAME'
        ],
    ]
];
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
        <div class="col-md-10"> <?php $this->AdminForm->create($params); ?> </div>
        <div class="col-md-1">&nbsp;</div>
    </div>


    <script>
    var subject = "";
        $(function () {

            $('#topBreadcrumb').hide();

            CKEDITOR.config.placeholder_select = {
                placeholders: <?= json_encode(["REFERRAL_URL","VERIFY_URL"]) ?>,
                format: '[%placeholder%]'
            };
            
            $('#Subject').focus(function () {
                if(subject.length <= 0){
                    subject = $(this).val();
                }
            });

            $('#Subject').keyup(function(){
                setNewsLetterUrl();
            }).keydown(function(){
                setNewsLetterUrl();
            });

            function setNewsLetterUrl() {
                //https://www.morninginvest.com/newsletter/92-million-voted-already-november-02-2020/
                //https://www.morninginvest.com/newsletter/62-days-for-december-24-2020
                var basicUrl = "https://www.redacted.inc/newsletter/slug-<?= strtolower(date("F-d-Y")); ?>";

                var newSubject = $('#Subject').val();

                if(subject != newSubject){
                    var slug = newSubject.toLowerCase().replace(/[^\w-]+/g,' ');
                    slug = $.trim(slug);
                    slug = slug.replace(/ /g,'-')
                    basicUrl = basicUrl.replace("slug", slug);

                    $('#NewsletterUrl').val(basicUrl);
                }

            }
            
        });

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
                    //Do something -here
                }
            });

        }, 10000);
        
        


    </script>
<?= $this->element('media') ?>
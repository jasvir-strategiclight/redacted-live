<?php //date_default_timezone_set("America/New_York");  ?>
<?= $this->Html->css(['vendor/flatpickr/dist/css/flatpickr.min']) ?>
<?= $this->Html->script(['vendor/flatpickr/dist/js/flatpickr.min', 'components/hs.range-datepicker']) ?>
<div class="g-bg-lightblue-v10-opacity-0_5 g-pa-20">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-lightblue-v4 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-envelope g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black"><?= $totalEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Scheduled Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>

        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue-v2 g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-send g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span
                                        class="g-font-size-24 g-line-height-1 g-color-black"><?= $sentEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Sent Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>

        <div class="col-sm-6 col-lg-6 col-xl-4 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-envelope-open g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span
                                        class="g-font-size-24 g-line-height-1 g-color-black"><?= $openedEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Opened Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <?php if($totalEmails > 0){ ?>
        <form method="post" accept-charset="utf-8" id="delete_scheduled_form" action="<?= SITE_URL.'/admin/admins/deleteScheduledEmail/scheduled'; ?>"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div>        
        <button data-modal-target="#deleteConfirmModal" data-modal-effect="slide" class="btn-u btn-u-red btn-u-sm rounded delete-btn" style="float: left; margin-left: 18px;margin-top: 16px;padding:12px 12px;background: #e81c1c;" id="delete_scheduled_btn"><i class="hs-admin-close"></i> Cancel Scheduled
        </button>
        </form>
        <?php } ?>
    </div>

</div>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer[]|\Cake\Collection\CollectionInterface $customers
 */

$this->Heading->create('Scheduled Emails');

$params = [
    'fields' => [
        [
            'name'  => 'to_email',
            'label' => 'Email To',

        ],
        [
            'name'                 => 'email_template_id',
            'label'                => 'Label',
            'related_model_fields' => ['label'],
            'sortable'             => false
        ],
        [
            'name'                 => 'email_template_id',
            'label'                => 'Subject',
            'related_model_fields' => ['subject'],
            'sortable'             => false
        ],
        [
            'label' => 'Scheduled At',
            'name'  => 'send_at',
            'type'  => 'datetime',
        ],
        [
            'name' => 'status',
        ],
        [
            'label'         => 'Viewed',
            'name'          => 'is_seen',
            'type'          => 'status',
            'model'         => 'ScheduledEmails',
            'active_text'   => 'Seen',
            'inactive_text' => 'Unseen',
            'readonly'      => true,
        ],
    ],
    'search' => [
        'match' => [
            'ScheduledEmails' => ['to_email']
        ]
    ]
];

$this->AdminListing->create($params, [
    'Delete' => [
        'url' => [
            'controller' => 'Admins', 'action' => 'deleteScheduledEmail'
        ]
    ]
]);
?>
<script>
    $(function () {
        // initialization of range datepicker
        var moveDate = flatpickr('.js-range-datepicker', {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            static: true,
            onChange: function (selectedDates, dateStr, instance) {
                window.location.href = SITE_URL +'admin/admins/scheduledEmails/?send_at='+dateStr;
            }
        });


        $('.hs-admin-calendar').click(function () {
            $('#selectDateWrapper input').focus();
        });
    });
</script>


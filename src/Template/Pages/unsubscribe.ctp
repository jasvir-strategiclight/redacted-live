<?php
$inputClasses = 'form-control g-color-black g-bg-white g-bg-white--focus g-brd-gray-light-v4 g-brd-primary--hover rounded g-py-15 g-px-15';
$classes = "form-control u-select--v3-select u-sibling w-100 u-select--v3 g-pos-rel g-brd-gray-light-v4 g-rounded-4 mb-2";
$dropIconClasses = "d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15";
$priorities = [
    'Immediate' => 'Immediate',
    'Hourly'    => 'Hourly',
    'Daily'     => 'Daily',
    'Never'     => 'Never'
];
//
?>
<style>
    .box-shadow {
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.20);
    }
</style>
<div class="row m-5" style="margin-bottom: 390px !important;">
    <div class="col-md-3"></div>
    <div class="col-md-6 box-shadow">
        <?= $this->Form->create(null, [
            'id'  => 'addEmailPriorityForm',
        ]) ?>

        <div class="row">
            <div class="col-md-12 g-font-size-20 mb-3  g-color-white g-pa-10" style="background-color: #282561 !important; padding: 0px; margin: 0px">
                <b>Unsubscribe From <?= SITE_TITLE; ?></b>
            </div>

            <div class="col-md-12 g-font-size-20 g-color-primary my-4">
                <b>Your Email Address:</b> <?= $user->email; ?>

            </div>
        </div>

        <div class="row mb-3">
            <div class="col-xs-12 col-sm-12">
                <div class="row">
                    <div class="col-md-8 col-sm-5">
                        <label class="g-color-gray-dark-v2 g-font-weight-600 g-font-size-16 mt-4">
                            Are you sure you want to unsubscribe?</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 align-self-center text-right">
                <?= $this->Form->button(__('Unsubscribe'), [
                    'id'    => 'realtorInfoBtn',
                    'class' => "btn btn-md g-bg-orange g-color-white rounded g-py-13 g-px-25 g-font-weight-600"
                ]) ?>
                <a href="<?= $this->Url->build(['controller'=>'Pages', 'action'=>'home']); ?>" class="btn btn-md u-btn-danger rounded g-py-13 g-px-25 g-font-weight-600 g-color-white"  style="background-color: #282561 !important">Cancel</a>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#addEmailPriorityForm").validate({
                    ignore: ":hidden:not(.not-ignore)",
                    rules: {
                        'apartment_special_priority': {required: true},
                        'apartment_replies_priority': {required: true},
                    },
                    messages: {
                        'apartment_special_priority': {required: "Please select apartment special email priority."},
                        'apartment_replies_priority': {required: "Please select apartment reply email priority."},
                    }
                });

                $('#NeverToBoth').click(function () {
                    if ($(this).is(':checked')) {
                        $('#realtorApartmentRepliesPriority, #realtorApartmentSpecialEmailPriority').val('Never');
                        $("#realtorApartmentRepliesPriority").rules("add", {required: false});
                        $("#realtorApartmentSpecialEmailPriority").rules("add", {required: false});
                    }
                });
            });
        </script>

    </div>
    <div class="col-md-2"></div>
</div>

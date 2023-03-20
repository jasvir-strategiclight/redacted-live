<?php
$params = [
    'fields'     => [
        [
            'label'     => "Name",
            'name'      => [
                ['name' => 'first_name'],
                ['name' => 'last_name'],
            ],
            "separator" => " "
        ],
        ['name' => 'email'],
        ['name' => 'phone'],
        ['name' => 'address'],
        ['name' => 'city'],
        ['name' => 'state'],
        ['name' => 'zip'],
        ['name' => 'no_of_affiliates'],
    ],
    'controller' => 'Apartments',
];
?>
<style>
    #topBreadcrumb {
        display: none;
    }
</style>
<div class="row">
    <div class="col-md-8">
        <div class="card g-brd-primary g-rounded-3 g-mb-30">
            <header class="card-header g-bg-primary g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                <div class="media">
                    <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-white g-mr-10 mb-0">
                        <i class="fa fa-trophy pt-2 pr-2"></i> Rewards</h3>
                </div>
            </header>

            <div class="card-block g-pa-15 g-pa-30--sm">
                <div class="row">
                    <?php foreach ($userRewards as $userReward) { ?>
                        <div class="col-md-2">
                            <img src="<?= SITE_URL; ?><?= $userReward->reward->has('image') ? $userReward->reward->image->small_thumb : 'files/images/default.jpg'; ?>"
                                 height="200px;"><br/>
                            <?php if ($userReward->delivery_status == "Delivered") { ?>
                                <a class="u-badge-v2--lg u-badge--top-right g-width-32 g-height-32 g-bg-darkblue g-bg-primary--hover g-mb-20 g-mr-20 box-shadow rounded-circle"
                                   href="javascript:void(0);" title="Delivered">

                                    <i class="fa fa-check g-absolute-centered g-font-size-16 g-color-white"></i>
                                </a>
                            <?php } ?>
                            <h5><?= $userReward->reward->name; ?></h5>
                            <?php if ($userReward->reward->name != "Sunday Newsletter") { ?>
                                <?php if ($userReward->delivery_status == "Pending") { ?>
                                    <button class="btn btn-sm btn-primary mark-delivered"
                                            data-id="<?= $userReward->id; ?>">
                                        <i class="fa fa-check"></i> Mark Delivered
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-primary mark-undelivered"
                                            data-id="<?= $userReward->id; ?>">
                                        <i class="fa fa-check"></i> Mark Undelivered
                                    </button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4"><?php $this->AdminDetail->info($params, "Details"); ?></div>
    <div class="col-md-12">
        <?php if (!empty($affiliates->toArray())) { ?>

            <hr/>
            <div class="card g-brd-primary g-rounded-3 g-mb-30">
                <header class="card-header g-bg-primary g-brd-bottom-none g-px-15 g-px-30--sm g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex align-self-center text-uppercase g-font-size-12 g-font-size-default--md g-color-white g-mr-10 mb-0">
                            <i class="fa fa-share-alt pt-2 pr-2"></i> Affiliates </h3>
                    </div>
                </header>

                <div class="card-block g-pa-15 g-pa-30--sm">
                    <ul>
                        <?php foreach ($affiliates as $affiliate) { ?>
                            <li>
                                <a href="<?= SITE_URL; ?>admin/users/view/<?= $affiliate->id; ?>"
                                   target="_blank"><?= $affiliate->email ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

        <?php } ?>
    </div>
</div>
<script>
    $(function () {
        $('.mark-delivered').click(function (e) {
            e.preventDefault();

            $.get(SITE_URL + "admin/users/markDelivered/" + $(this).attr('data-id'), function () {
                window.location.reload();
            });
        });

        $('.mark-undelivered').click(function (e) {
            e.preventDefault();

            $.get(SITE_URL + "admin/users/markUndelivered/" + $(this).attr('data-id'), function () {
                window.location.reload();
            });
        });
    });
</script>


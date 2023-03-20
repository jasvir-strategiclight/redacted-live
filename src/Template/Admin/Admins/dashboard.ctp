<?php $this->assign('title', __('Dashboard')) ?>
<?= $this->Html->script([
    'vendor/chartist-js/chartist.min.js',
    'vendor/chartist-js-tooltip/chartist-plugin-tooltip.js',
    'components/hs.area-chart',
    'components/hs.donut-chart',
    'components/hs.bar-chart',
    'components/hs.pie-chart.js',
]) ?>

<div class="g-bg-lightblue-v10-opacity-0_5 g-pa-20">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-xl-3 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-pink g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-user g-absolute-centered"></i>
                            </div>
                        </div>
                        
                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black"><?= $totalUsers ?></span>
                            </div>
                            
                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Users</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        

        <div class="col-sm-6 col-lg-6 col-xl-3 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-darkblue g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                <i class="fa fa-envelope g-absolute-centered"></i>
                            </div>
                        </div>

                        <div class="media-body align-self-center">
                            <div class="d-flex align-items-center g-mb-5">
                                <span class="g-font-size-24 g-line-height-1 g-color-black"><?= $scheduledEmails; ?></span>
                            </div>

                            <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Scheduled Emails</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>

        <div class="col-sm-6 col-lg-6 col-xl-3 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-orange g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
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

        <div class="col-sm-6 col-lg-6 col-xl-3 g-mb-0">
            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                <div class="card-block g-font-weight-300 g-pa-20">
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div
                                    class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-green  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
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
    </div>
    <div class="row mt-5">
        <div class="col-md-6 ">
            <?php

            $totalEmails = $scheduledEmails + $sentEmails;


            $sentEmailsPercentage = round((($sentEmails / $totalEmails) * 100), 2);
            $openedEmailsPercentage = round((($openedEmails / $totalEmails) * 100), 2);
            $pendingEmailsPercentage = round((($scheduledEmails / $totalEmails) * 100), 2);
            ?>

            <!-- Panel -->
            <div class="card h-100 g-brd-gray-light-v7 rounded pl-5">

                <div class="card-block g-font-weight-300 g-pa-20">
                    <ul class="list-unstyled d-flex g-mb-45">
                        <li class="media">
                            <div class="d-flex align-self-center g-mr-8">
                                <span
                                        class="u-badge-v2--md g-pos-stc g-transform-origin--top-left g-bg-lightblue-v4"></span>
                            </div>

                            <div class="media-body align-self-center g-font-size-12 g-font-size-default--md">Scheduled Emails
                            </div>
                        </li>
                        <li class="media g-ml-5 g-ml-35--md">
                            <div class="d-flex align-self-center g-mr-8">
                                <span
                                        class="u-badge-v2--md g-pos-stc g-transform-origin--top-left g-bg-darkblue-v2"></span>
                            </div>

                            <div class="media-body align-self-center g-font-size-12 g-font-size-default--md">Sent Emails
                            </div>
                        </li>
                        <li class="media g-ml-5 g-ml-35--md">
                            <div class="d-flex align-self-center g-mr-8">
                                <span
                                        class="u-badge-v2--md g-pos-stc g-transform-origin--top-left g-bg-orange"></span>
                            </div>

                            <div class="media-body align-self-center g-font-size-12 g-font-size-default--md">
                                Opened Emails
                            </div>
                        </li>
                    </ul>
                    <div class="media">
                        <div class="d-flex g-mr-15">
                            <div class="js-pie-chart g-pos-rel mx-auto" style="width: 400px; height: 400px;"
                                 data-series='[<?= $pendingEmailsPercentage; ?>, <?= $sentEmailsPercentage; ?>, <?= $openedEmailsPercentage; ?>]'
                                 data-start-angle="330"
                                 data-fill-colors='["#3dd1e8", "#1d75e5", "#e57d20"]'></div>
                        </div>

                        <div class="media-body align-self-center">
                            <h4 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0"> &nbsp;&nbsp; Email
                                Ratio</h4>

                            <div class="d-flex">
                                <div class="d-flex align-items-center g-color-gray-dark-v10 g-ml-10 font-bold">
                                    <span style="color: #3dd1e8;" title="Realtors"><?= $pendingEmailsPercentage; ?>%</span>
                                    &nbsp;|&nbsp;
                                    <span style="color: #1d75e5;"
                                          title="Apartment Managers"><?= $sentEmailsPercentage; ?>%</span> &nbsp;|&nbsp;
                                    <span style="
                                    color: #e57d20;"
                                          title="Regional Managers"><?= $openedEmailsPercentage; ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->

        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                        <div class="card-block g-font-weight-300 g-pa-20">
                            <div class="media">
                                <div class="d-flex g-mr-15">
                                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-green  g-font-size-18 g-font-size-24--md g-color-white rounded-circle" style="background-color: #279956">
                                        <i class="fa fa-user-o g-absolute-centered"></i>
                                    </div>
                                </div>

                                <div class="media-body align-self-center">
                                    <div class="d-flex align-items-center g-mb-5">
                                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($newUserCount) ? 0 : $newUserCount; ?></span>
                                    </div>

                                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">New Subscribers in Last 24 hours</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <!--<div class="col-md-12 mt-3">-->
                <!--    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">-->
                <!--        <div class="card-block g-font-weight-300 g-pa-20">-->
                <!--            <div class="media">-->
                <!--                <div class="d-flex g-mr-15">-->
                <!--                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-blue  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">-->
                <!--                        <i class="fa fa-users g-absolute-centered"></i>-->
                <!--                    </div>-->
                <!--                </div>-->

                <!--                <div class="media-body align-self-center">-->
                <!--                    <div class="d-flex align-items-center g-mb-5">-->
                <!--                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($newsletterCount) ? 0 : $newsletterCount; ?></span>-->
                <!--                    </div>-->

                <!--                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Sunday Newsletter Users</h6>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<br />-->
                <div class="col-md-12 mt-3">
                    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                        <div class="card-block g-font-weight-300 g-pa-20">
                            <div class="media">
                                <div class="d-flex g-mr-15">
                                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-gray-dark-v8  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="fa fa-users g-absolute-centered"></i>
                                    </div>
                                </div>

                                <div class="media-body align-self-center">
                                    <div class="d-flex align-items-center g-mb-5">
                                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($campaignLeadsCounts) ? 0 : $campaignLeadsCounts; ?></span>
                                    </div>

                                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Campaign Leads in Last 24 hours</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                        <div class="card-block g-font-weight-300 g-pa-20">
                            <div class="media">
                                <div class="d-flex g-mr-15">
                                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-gray-dark-v8  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="fa fa-users g-absolute-centered"></i>
                                    </div>
                                </div>

                                <div class="media-body align-self-center">
                                    <div class="d-flex align-items-center g-mb-5">
                                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($campaignCounts) ? 0 : $campaignCounts; ?></span>
                                    </div>

                                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Campaign Leads</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="col-md-12 mt-3">
                    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                        <div class="card-block g-font-weight-300 g-pa-20">
                            <div class="media">
                                <div class="d-flex g-mr-15">
                                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60 g-bg-gray-dark-v8  g-font-size-18 g-font-size-24--md g-color-white rounded-circle">
                                        <i class="fa fa-users g-absolute-centered"></i>
                                    </div>
                                </div>

                                <div class="media-body align-self-center">
                                    <div class="d-flex align-items-center g-mb-5">
                                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($unsubscriberCount) ? 0 : $unsubscriberCount; ?></span>
                                    </div>

                                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Unsubscribers</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="col-md-12 mt-3">
                    <div class="card h-100 g-brd-gray-light-v7 u-card-v1 g-rounded-3">
                        <div class="card-block g-font-weight-300 g-pa-20">
                            <div class="media">
                                <div class="d-flex g-mr-15">
                                    <div class="u-header-dropdown-icon-v1 g-pos-rel g-width-60 g-height-60  g-font-size-18 g-font-size-24--md g-color-white rounded-circle" style="background-color: #278e56">
                                        <i class="fa fa-envelope-open g-absolute-centered"></i>
                                    </div>
                                </div>

                                <div class="media-body align-self-center">
                                    <div class="d-flex align-items-center g-mb-5">
                                        <span class="g-font-size-24 g-line-height-1 g-color-black"><?= empty($currentCampaignOpenRate) ? 0 : number_format((float)$currentCampaignOpenRate, 2, '.', ''); ?>%</span>
                                    </div>

                                    <h6 class="g-font-size-16 g-font-weight-300 g-color-gray-dark-v6 mb-0">Email Open Rate from  Last Email Campaign</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    $(document).on('ready', function () {
        // initialization of hamburger
        $.HSCore.helpers.HSHamburgers.init('.hamburger');

        // initialization of charts
        $.HSCore.components.HSAreaChart.init('.js-area-chart');
        $.HSCore.components.HSDonutChart.init('.js-donut-chart');
        $.HSCore.components.HSBarChart.init('.js-bar-chart');
        $.HSCore.components.HSPieChart.init('.js-pie-chart');
        
        // initialization of custom scrollbar
        $.HSCore.components.HSScrollBar.init($('.js-custom-scroll'));
        
        setTimeout(function () {
            $('.message').fadeOut().remove();
        }, 4000);
        
    });
</script>


<?php $this->assign('title', __('Dashboard')) ?>
<style>
    .box-shadow {
        box-shadow: #f889a8 0px 0px 10px 4px;
    }
</style>
<div class="g-bg-lightblue-v10-opacity-0_5 g-pa-20">
        <!-- Promo Block -->
        <section
            class="dzsparallaxer auto-init height-is-based-on-content use-loading mode-scroll loaded dzsprx-readyall "
            data-options='{direction: "fromtop", animation_duration: 25, direction: "reverse"}'>
            <!-- Parallax Image -->
            <div
                class="divimage dzsparallaxer--target w-100 g-bg-cover g-bg-size-cover g-bg-pos-top-center g-bg-primary-gradient-opacity-v1--after"
                style="height: 70%; background-image: url(images/pricing-plan.jpg);"></div>
            <!-- End Parallax Image -->
            
            <!-- Promo Block Content -->
            <div class="container g-color-white text-center g-pos-rel g-z-index-1 g-pt-50 g-pb-100">
                <h3 class="h2 g-font-weight-300 mb-0" style="color: #000000">Finding your perfect plan.</h3>
                <h2 class="g-font-weight-700 g-font-size-65 text-uppercase" style="color: #000000">Pricing plan</h2>
                <h4 class="g-font-weight-300  text-uppercase" style="color: #000000">Purchase Package of your choice to
                    enjoy the services.</h4>
            </div>
            <!-- Promo Block Content -->
        </section>
        <div class="row">
            <?php foreach ($subscriptionPackages as $subscriptionPackage) { ?>
                <div class="col-md-4 g-mb-30 fancybox-show-infobar">
                    <!-- Article -->
                    <article
                        class="u-shadow-v21 u-shadow-v21--hover g-bg-white text-center g-overflow-hidden g-rounded-4 g-pos-rel g-z-index-2 g-cursor-pointer g-transition-0_3"
                        id="plan_<?= $subscriptionPackage->id; ?>">
                        <!-- Article Header -->
                        <header class="g-bg-primary g-pos-rel g-px-20 g-py-70"><strong
                                class="d-block g-color-white g-font-size-50 g-line-height-0_7 g-mb-20"> <span
                                    class="g-valign-top g-font-size-default">INR</span><?= $subscriptionPackage->price ?>
                                <span
                                    class="g-font-size-default"></span> </strong>
                            <h3 class="h6 text-uppercase g-color-white-opacity-0_7 g-letter-spacing-3 g-mb-20"><?= $subscriptionPackage->name ?></h3>
                        </header>
                        <!-- End Article Header -->
                        
                        <!-- Article Content -->
                        <div class="g-px-20 g-py-40">
                            <ul class="list-unstyled g-mb-40">
                                <li class="g-mb-20">Package <?= $subscriptionPackage->price ?></li>
                                <li class="g-mb-20">Earn <?= $subscriptionPackage->earn_per_sms ?> paisa / Sms</li>
                                <li class="g-mb-20">Cost <?= $subscriptionPackage->price ?> INR</li>
                                <li class="g-mb-20">Pay By Paytm <?= $paytmNumber ?></li>
                            </ul>
                            <a href="<?= $this->Url->build(['controller' => 'Subscriptions', 'action' => 'subscribe', $subscriptionPackage->id]); ?>">
                                <button
                                    class=" btn text-uppercase u-btn-primary g-rounded-50 g-font-size-12 g-font-weight-700 g-pa-15-30 g-mb-10">
                                    Purchase
                                </button>
                            </a>
                        </div>
                        <!-- End Article Content -->
                    </article>
                    <!-- End Article -->
                </div>
            <?php } ?>
        </div>
    <?php /*
        <?= $this->element('home_header', [
            'subscriptionPackages'=>$subscriptionPackages,
            'authUser'=>$authUser,
            'paytmNumber'=>$paytmNumber
            ]); ?>
            */
    ?>
        <script>
            $(function () {
                var plan = localStorage.getItem("SelectedPlan");
                $('#' + plan).addClass('box-shadow');
            });
        </script>
    
    <!-- Statistic Card -->
    <div class="row">
        <div class="col-xl-12"></div>
    </div>
</div>





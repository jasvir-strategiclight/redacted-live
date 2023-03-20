<!-- Breadcrumbs -->
<section class="g-bg-gray-light-v5">
    <div class="container g-py-50">
        <div class="d-sm-flex text-center">
            <div class="align-self-center">
                <h2 class="h3 g-font-weight-300 w-100 g-mb-10 g-mb-0--md">Frequently Asked Questions</h2>
            </div>
            
            <div class="align-self-center ml-auto">
            
            </div>
        </div>
    </div>
</section>
<!-- End Breadcrumbs -->

<!-- Promo Block -->
<!--section class="container text-center g-pt-120 g-pb-30">
    <h2 class="g-color-black g-font-weight-600 g-font-size-50 g-mb-70">Frequently Asked Questions</h2>
</section -->
<!-- End Promo Block -->

<!-- Accordion -->
<section class="container g-pb-100">
    <!-- Accordion -->
    <div id="purchase" class="u-shadow-v11 rounded g-py-100 g-mb-100">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Heading -->
                <!-- End Heading -->
                
                <div id="accordion" class="u-accordion u-accordion-color-primary" role="tablist"
                     aria-multiselectable="true">
                    <?php foreach ($faqs as $faq) { ?>
                        <!-- Card -->
                        <div class="card g-brd-none rounded-0 g-mb-20">
                            
                            
                            <div id="accordion-heading-<?= $faq->id ?>" class="g-brd-bottom g-brd-gray-light-v4 g-pa-0"
                                 role="tab">
                                <h5 class="mb-0">
                                    <a class="collapsed d-flex justify-content-between g-color-main g-text-underline--none--hover rounded-0 g-px-30 g-py-20"
                                       href="#accordion-body-<?= $faq->id ?>" data-toggle="collapse" data-parent="#accordion"
                                       aria-expanded="false" aria-controls="accordion-body-01">
                                        <?= $faq->question ?>
                                        <span class="u-accordion__control-icon">
                          <i class="fa fa-angle-down"></i>
                          <i class="fa fa-angle-up"></i>
                        </span>
                                    </a>
                                </h5>
                            </div>
                            <div id="accordion-body-<?= $faq->id ?>" class="collapse" role="tabpanel"
                                 aria-labelledby="accordion-heading-01" data-parent="#accordion">
                                <div class="u-accordion__body g-color-gray-dark-v4 g-pa-30">
                                    <?= $faq->answer ?>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Accordion -->


</section>
<!-- End Accordion -->

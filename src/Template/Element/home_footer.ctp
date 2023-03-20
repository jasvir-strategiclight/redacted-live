<?php

$menuItems = [
    'Userslogin' => '',
    'Usersregister' => '',
    'Usershome' => '',
    'Pagesabout' => '',
    'Pagescontact' => '',
    'PageshowToWork' => '',
    'Plans' => '',
    'Faqsindex' => '',
    'PagesprivacyPolicy' => '',
    'PagestermsAndConditions' => ''
];

$menuItems[$this->request->getParam('controller') . $this->request->getParam('action')] = 'active';
?>


<!-- Copyright Footer -->
<div class="container-fluid p-0" style="width:100%;">
    <div class="col-md-12 p-0">
        <footer class="g-bg-white g-color-dark g-py-20 g-px-20">
            <div class="row">
                <div class="col-md-12 text-md-left g-mb-10 g-mb-0--md">
                    <div class="text-center">
                        <small class="d-block g-font-size-default g-mr-10 g-mb-10 g-mb-0--md">
                            <?= date('Y'); ?> &copy; <?= SITE_TITLE ?> All Rights Reserved. &nbsp;&nbsp; <a href="https://redacted.inc/privacy-policy/" target="_blan">Privacy Policy</a> |  <a href="https://redacted.inc/terms-conditions/" target="_blan">Terms and Conditions</a>
                        </small>   
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<!-- End Copyright Footer -->
<a class="js-go-to u-go-to-v1" href="#!" data-type="fixed" data-position='{
     "bottom": 15,
     "right": 15
   }' data-offset-top="400" data-compensation="#js-header" data-show-effect="zoomIn">
    <i class="hs-icon hs-icon-arrow-top"></i>
</a>

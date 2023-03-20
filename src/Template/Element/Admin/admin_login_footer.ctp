<!-- Footer -->
<!-- div id="contacts-section" class="g-color-white-opacity-0_8 g-py-10 text-center">
    <a href="<?= SITE_URL ?>" class="navbar-brand">
        <?= $this->Html->image('logo-aptnet.png', ['alt' => SITE_TITLE, 'height' => '100px']); ?>
    </a>
</div -->
<!-- Copyright Footer -->
<footer class="g-bg-gray-dark-v1 g-color-white-opacity-0_8 g-py-20 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center text-md-left g-mb-10 g-mb-0--md">
                <div class="d-lg-flex">
                    <small class="d-block g-font-size-default g-mr-10 g-mb-10 g-mb-0--md">2018 &copy; All Rights
                        Reserved.
                    </small>
                    <ul class="u-list-inline">
                        <li class="list-inline-item">
                            <a class="g-color-white-opacity-0_8 g-color-white--hover"
                               href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'privacyPolicy']); ?>">Privacy
                                Policy</a>
                        </li>
                        <li class="list-inline-item">
                            <span>|</span>
                        </li>
                        <li class="list-inline-item">
                            <a class="g-color-white-opacity-0_8 g-color-white--hover"
                               href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'termsAndConditions']); ?>">Terms
                                & Conditions</a>
                        </li>
                    
                    </ul>
                </div>
            </div>
            
            <div class="col-md-4 align-self-center">
                <ul class="list-inline text-center text-md-right mb-0">
                    <li class="list-inline-item g-mx-10" data-toggle="tooltip" data-placement="top" title="Facebook">
                        <a href="#!" class="g-color-white-opacity-0_5 g-color-white--hover">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    <li class="list-inline-item g-mx-10" data-toggle="tooltip" data-placement="top" title="youtube">
                        <a href="#!" class="g-color-white-opacity-0_5 g-color-white--hover">
                            <i class="fa fa-youtube"></i>
                        </a>
                    </li>
                
                </ul>
            </div>
        </div>
    </div>
</footer>

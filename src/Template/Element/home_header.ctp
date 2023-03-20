<style>
    #collapsNav li a {
        color: #ffffff !important;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 p-0">
            <div class="u-header__section u-header__section--light g-bg-white g-transition-0_3"
                 data-header-fix-moment-exclude="g-py-15" data-header-fix-moment-classes="u-shadow-v18 g-py-7">

                <div class=" flex-md-row align-items-center g-pos-rel">
                    <!-- Logo -->
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'home']); ?>"
                       class="navbar-brand ">
                        <?= $this->Html->image('logo.png', ['alt' => SITE_TITLE, 'style'=>'height:50px;']); ?>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

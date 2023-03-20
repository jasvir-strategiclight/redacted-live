

<header id="js-header"
        class="u-header u-header--static u-header--show-hide u-header--change-appearance u-header--has-hidden-element"
        data-header-fix-moment="500" data-header-fix-effect="slide">
    <div class="u-header__section u-header__section--admin-dark g-transition-0_3 g-py-15 box-shadow"
         data-header-fix-moment-exclude="g-py-15" data-header-fix-moment-classes="u-shadow-v18 g-py-7">
        <div class="container">
            <div class="d-lg-flex flex-md-row align-items-center g-pos-rel">
                <!-- Responsive Toggle Button -->
                <button
                    class="navbar-toggler navbar-toggler-right btn g-hidden-lg-up g-line-height-1 g-brd-none g-pa-0 g-pos-abs g-top-3 g-right-0"
                    type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navBar"
                    data-toggle="collapse" data-target="#navBar">
              <span class="hamburger hamburger--slider">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
              </span>
              </span>
                </button>
                <!-- End Responsive Toggle Button -->
                <!-- Logo -->
                <a href="<?= SITE_URL ?>" class="navbar-brand py-5">
                    <?= $this->Html->image('logo.png', ['alt' => SITE_TITLE]); ?>
                </a>
                <!-- End Logo -->
                
                <div class="col g-mt-10 g-mt-0--lg g-px-0">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
</header>

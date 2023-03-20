<!-- Header -->
<header id="js-header" class="u-header u-header--sticky-top">
    <div class="u-header__section u-header__section--admin g-bg-white g-min-height-65 box-shadow">
        <nav class="navbar no-gutters g-pa-0">
            <div class="col-auto d-flex flex-nowrap u-header-logo-toggler g-py-12 g-bg-white">
                <!-- Logo -->
                <a href="<?= SITE_URL . "/admin" ?>"
                   class="navbar-brand d-flex align-self-center g-hidden-xs-down g-line-height-1 py-0 g-mt-5">
                    <?= $this->Html->image('logo.png', ['alt' => SITE_TITLE, 'height' => '34px']); ?>
                </a>
                <!-- End Logo -->

                <!-- Sidebar Toggler -->
                <a class="js-side-nav u-header__nav-toggler d-flex align-self-center ml-auto" href="#!"
                   data-hssm-class="u-side-nav--mini u-sidebar-navigation-v1--mini"
                   data-hssm-body-class="u-side-nav-mini" data-hssm-is-close-all-except-this="true"
                   data-hssm-target="#sideNav" id="hideShowSidebar">
                    <i class="hs-admin-align-left g-color-black"></i>
                </a>
                <!-- End Sidebar Toggler -->
            </div>

            <!-- Top User -->
            <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-10 g-pl-20--sm">
                <div class="g-pos-rel g-px-10--lg">
                    <a id="profileMenuInvoker" class="d-block" href="#!" aria-controls="profileMenu"
                       aria-haspopup="true" aria-expanded="false" data-dropdown-event="click"
                       data-dropdown-target="#profileMenu" data-dropdown-type="css-animation"
                       data-dropdown-duration="300"
                       data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                <span class="g-pos-rel">
        <span class="u-badge-v2--xs u-badge--top-right g-hidden-sm-up g-bg-lightblue-v5 g-mr-5"></span>
                    <?php $image = SITE_URL . (($authUser->has('image')) ? $authUser->image->small_thumb : 'files/images/default.png'); ?>
                    <img src="<?= $image ?>" alt='<?php $authUser['name'] ?>'
                         class="g-width-30 g-width-40--md g-height-30 g-height-40--md rounded-circle g-mr-10--sm"/>
                </span>
                        <span class="g-pos-rel g-top-2">
        <span class="g-hidden-sm-down "><?= $authUser['name'] ?> </span>
                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                </span>
                    </a>

                    <!-- Top User Menu -->
                    <ul id="profileMenu"
                        class="g-pos-abs g-right-0 g-width-100x--lg g-bg-white g-nowrap g-font-size-14 g-p-20 g-mt-17 rounded"
                        aria-labelledby="profileMenuInvoker">
                        <li class="g-mb-10">
                            <a class="media g-color-lightred-v2--hover g-py-5 g-px-20"
                               href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'profile']); ?>">
                    <span class="d-flex align-self-center g-mr-12">
          <i class="hs-admin-user"></i>
        </span>
                                <span class="media-body align-self-center">My Profile</span>
                            </a>
                        </li>
                        <li class="mb-0">
                            <a class="media g-color-lightred-v2--hover g-py-5 g-px-20"
                               href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'logout']); ?>">
                    <span class="d-flex align-self-center g-mr-12">
          <i class="hs-admin-shift-right"></i>
        </span>
                                <span class="media-body align-self-center">Sign Out</span>
                            </a>
                        </li>
                    </ul>
                    <!-- End Top User Menu -->
                </div>
            </div>
            <!-- End Top User -->
    </div>
    <!-- End Messages/Notifications/Top Search Bar/Top User -->

    <!-- Top Activity Toggler -->
    <a id="activityInvoker"
       class="text-uppercase u-header-icon-v1 g-pos-rel g-width-40 g-height-40 rounded-circle g-font-size-20" href="#!"
       aria-controls="activityMenu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click"
       data-dropdown-target="#activityMenu"
       data-dropdown-type="css-animation" data-dropdown-animation-in="fadeInRight"
       data-dropdown-animation-out="fadeOutRight" data-dropdown-duration="300">
        <i class="g-absolute-centered"></i>
    </a>

    <!-- End Top Activity Toggler -->
    </nav>
    </div>
</header>

<!-- End Header -->

<?php //pr($content); ?>
<section class="g-pos-rel">
    <div class="dzsparallaxer auto-init height-is-based-on-content use-loading mode-scroll"
         data-options='{direction: "reverse", settings_mode_oneelement_max_offset: "150"}'>
        <!-- Carousel Slider -->
        <div class="js-carousel u-carousel-v5"
             data-infinite="true"
             data-autoplay="true"
             data-speed="8000"
             data-pagi-classes="u-carousel-indicators-v34 g-absolute-centered--y g-left-auto g-right-30 g-right-100--md"
             data-calc-target="#js-header" style="">
            
            <!-- Carousel Slides -->
            <div class="js-slide h-100 g-flex-centered g-bg-img-hero g-bg-black-opacity-0_2--after"
                 style="background-image: url(img/full_banner.jpg);">
                <div class="container g-bg-cover__inner g-py-100">
                    
                    <div class="row g-ml-100">
                        <div class="col-lg-8">&nbsp;</div>
                        <div class="col-lg-4 g-mb-30 g-mb-0--lg tcenter">
                            <h2 class="h2 g-color-black  font-bold size36">
                                <span class=" g-px-5--lg "><?= $content['Banner Heading']['heading']; ?></span>
                            </h2>
                            <h3 class="h3 size18 g-color-black" style="font-size:18px;">
                                <?= $content['Banner Heading']['text']; ?>
                            </h3>
                            <div class="d-inline-block g-hidden-xs-down g-pos-rel g-valign-middle g-pl-30 g-pl-0--lg ">
                                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']); ?>"
                                   class="btn btn-special u-btn-primary g-font-size-18 text-uppercase g-py-10 g-px-15 bold " style="border:1px solid #000; color:#000;">
								   <i class="fa fa-arrow-right bounce g-font-size-18 "></i> GET STARTED</a>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="g-pos-abs g-left-0 g-right-0 g-z-index-2 g-bottom-30 text-center" id="threeSections">
                    <a class="js-go-to btn g-color-white g-bg-white g-bg-white-opacity-0_1 g-color-black--hover g-bg-white--hover  g-font-weight-600 text-uppercase g-rounded-50 g-px-30 g-py-11  undefined"
                       href="#!" data-target="#threeSections" style="display: inline-block;">
                        <i class="fa fa-angle-down g-font-size-22"></i>
                    </a>
                </div>
            </div>
            <!-- End Carousel Slides -->
        
        </div>
        <!-- End Carousel Slider -->
    </div>
</section>
<!-- End Promo Block -->

<!-- Icon Blocks -->
<section class="g-py-20">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-lg-4 g-px-40 g-mb-50 g-mb-0--lg">
                <!-- Icon Blocks -->
                <div class="text-center">
                    <a
                        href="#apartmentModal"
                        id="apartmentModalBtn"
                        data-modal-target="#apartmentModal"
                        data-modal-effect="slit">
              <span
                  class="d-inline-block u-icon-v3 u-icon-size--xl g-bg-primary g-color-white rounded-circle g-mb-30 3-icons">
              <i class="icon-real-estate-002  u-line-icon-pro "></i>
                </span>
                        
                        <h3 class="h5 g-color-gray-dark-v2 g-font-weight-600 text-uppercase mb-3"><?= $content['First of 3 Sections']['heading']; ?></h3>
                    </a>
                    <p class="mb-0"><?= $content['First of 3 Sections']['text']; ?></p>
                </div>
                <!-- End Icon Blocks -->
            </div>
            
            <div class="col-lg-4 g-brd-left--lg g-brd-gray-light-v4 g-px-40 g-mb-50 g-mb-0--lg">
                <!-- Icon Blocks -->
                <div class="text-center">
                    <a  href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']); ?>">
              <span
                  class="d-inline-block u-icon-v3 u-icon-size--xl g-bg-primary g-color-white rounded-circle g-mb-30 3-icons">
                 <i class="icon-communication-180 u-line-icon-pro "></i>
                </span>
                        <h3 class="h5 g-color-gray-dark-v2 g-font-weight-600 text-uppercase mb-3"><?= $content['Second of 3 Sections']['heading']; ?></h3>
                    </a>
                    <p class="mb-0"><?= $content['Second of 3 Sections']['text']; ?></p>
                </div>
                <!-- End Icon Blocks -->
            </div>
            
            <div class="col-lg-4 g-brd-left--lg g-brd-gray-light-v4 g-px-40">
                <!-- Icon Blocks -->
                
                <div class="text-center">
                    <a
                        href="#realtorModal"
                        id="realtorModalBtn"
                        data-modal-target="#realtorModal"
                        data-modal-effect="slit">
              <span
                  class="d-inline-block u-icon-v3 u-icon-size--xl g-bg-primary g-color-white rounded-circle g-mb-30 3-icons">
                 <i class="icon-user-following u-line-icon-pro"></i>
                </span>
                        <h3 class="h5 g-color-gray-dark-v2 g-font-weight-600 text-uppercase mb-3"><?= $content['Third of 3 Sections']['heading']; ?></h3>
                    </a>
                    <p class="mb-0"><?= $content['Third of 3 Sections']['text']; ?></p>
                </div>
                
                <!-- End Icon Blocks -->
            </div>
        </div>
    </div>
</section>
<!-- End Icon Blocks -->

<hr class="g-brd-gray-light-v4 my-0">

<section class="container g-pt-50 g-pb-30">
    <div class="row">
        
        <div class="col-lg-4 align-self-center g-mb-20">
            <img src="<?= SITE_URL; ?>img/our-mission.png" alt="Apartment Network" class="img-fluid">
        </div>
		  <div class="col-lg-7 align-self-center g-mb-20">
            <div class="mb-2">
                <h2 class="h3 g-color-black text-uppercase mb-2"><?= $content['Our Mission']['heading']; ?></h2>
                <div class="d-inline-block g-width-100 g-height-2 g-bg-primary mb-2"></div>
            </div>
            <div class="mb-5">
                <p class="mb-5"><?= $content['Our Mission']['text']; ?></p>
            </div>
        </div>
		
        
    </div>
</section>

<hr class="g-brd-gray-light-v4 my-0">

<section class="g-py-0" style="padding-bottom:0!important">
    <div class="g-bg-cover g-bg-size-cover g-bg-pos-center g-bg-black-opacity-0_7--after"
         style="background-image: url(<?= SITE_URL; ?>img/img8.jpg);">
        
        <div class="container g-bg-cover__inner g-py-120">
            <div class=" g-brd-primary g-mb-20">
                <h2 class="h4 u-heading-v2__title g-color-white g-font-weight-600 text-uppercase text-center"><?= $content['Testimonials']['heading']; ?></h2>
            </div>
            <div class="js-carousel g-pb-80" data-infinite="true"
                 data-arrows-classes="u-arrow-v1 g-width-40 g-height-40 g-brd-1 g-brd-style-solid g-brd-white-opacity-0_6 g-brd-primary--hover g-color-white-opacity-0_5 g-bg-primary--hover g-color-white--hover g-absolute-centered--x g-bottom-0 rounded-circle"
                 data-arrow-left-classes="fa fa-angle-left g-ml-minus-25"
                 data-arrow-right-classes="fa fa-angle-right g-ml-25" data-autoplay="true">
                
                <?php foreach ($testimonials as $testimonial) { ?>
                    <div class="js-slide">
                        <!-- Testimonials Advanced -->
                        <div class="row justify-content-center text-center" style="width: 100%;">
                            <div class="col-lg-8">
                                <div class="d-flex justify-content-between">
                                    <img
                                        class="text-left g-brd-around g-brd-4 g-brd-primary g-width-100 g-height-100 rounded-circle mr-5"
                                        src="<?= SITE_URL . $testimonial->image->small_thumb; ?>"
                                        alt="Image Description">
                                    
                                    <div class="text-left g-color-white text-sm-left"
                                         style="width: 100%; margin-top: 1%;">
                                        <p class="font-normal font-italic g-font-size-18">
                                            <i class="fa fa-quote-left" style="margin-top: -10px;"></i> <?= $testimonial->testimonial; ?> <i class="fa fa-quote-right"></i>
                                        </p>
                                        <h4 class="h5 g-mb-0 text-left"><?= $testimonial->user_name; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Testimonials Advanced -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<hr class="g-brd-gray-light-v4 my-0">
<!-- Our Recent Projects -->
<section class="g-py-100" style="padding-bottom:0!important">
    <div class="container">
        <header class="g-mb-50">
            <div class="u-heading-v2-3--bottom g-brd-primary g-mb-20">
                <h2 class="h4 u-heading-v2__title g-color-gray-dark-v2 g-font-weight-600 text-uppercase"><?= $content['Our Members']['heading']; ?></h2>
            </div>
            <p class="g-font-size-16"><?= $content['Our Members']['text']; ?>.</p>
        </header>
        
        <div class="container g-pos-rel g-z-index-1 g-pb-80">
            <div class="js-carousel"
                 data-infinite="true"
                 data-autoplay="true"
                 data-lazy-load="ondemand"
                 data-slides-show="6"
                 data-responsive='[{
               "breakpoint": 1200,
               "settings": {
                 "slidesToShow": 6
               }
             }, {
               "breakpoint": 992,
               "settings": {
                 "slidesToShow": 5
               }
             }, {
               "breakpoint": 768,
               "settings": {
                 "slidesToShow": 4
               }
             }, {
               "breakpoint": 576,
               "settings": {
                 "slidesToShow": 2
               }
             }, {
               "breakpoint": 446,
               "settings": {
                 "slidesToShow": 1
               }
             }]'>
                <div class="js-slide u-block-hover g-mr-10">
                    <img
                        class="mx-auto g-width-170 g-cursor-pointer "
                        data-lazy="img/img1.png" alt="Image description">
                </div>
                
                <div class="js-slide u-block-hover g-mr-10">
                    <img
                        class="mx-auto g-width-170 g-cursor-pointer "
                        data-lazy="img/img2.png" alt="Image description">
                </div>
                
                <div class="js-slide u-block-hover g-mr-10">
                    <img
                        class="mx-auto g-width-170 g-cursor-pointer"
                        data-lazy="img/img3.png" alt="Image description">
                </div>
                
                <div class="js-slide u-block-hover g-mr-10">
                    <img
                        class="mx-auto g-width-170 g-cursor-pointer"
                        data-lazy="img/img4.png" alt="Image description">
                </div>
                
                <div class="js-slide u-block-hover g-mr-10">
                    <img
                        class="mx-auto g-width-170 g-cursor-pointer"
                        data-lazy="img/img5.png" alt="Image description">
                </div>
                
            </div>
        </div>
    </div>
</section>
<!-- End Our Recent Projects -->





<!-- Latest News -->

<!-- End Latest News -->

<!-- Call To Action -->
<section class="g-bg-primary g-color-white g-pa-30" style="background-image: url(<?= SITE_URL; ?>img/pattern5.png);">
    <div class="d-md-flex justify-content-md-center text-center">
        <div class="align-self-md-center">
            <p class="lead g-font-weight-400 g-mr-20--md g-mb-15 g-mb-0--md"><?= $content['Become a member']['text']; ?></p>
        </div>
        <div class="align-self-md-center">
            <a class="btn btn-sm btn-special u-btn-outline-primary g-font-size-13 text-uppercase g-py-10 g-px-15 "
               style=""
               href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']); ?>">
                <i class="fa fa-user-circle-o g-font-size-18 "></i> Join now
            </a>
        </div>
    </div>
</section>
<!-- End Call To Action -->
<script>
    $(document).on('ready', function () {
        
        // $.HSCore.components.HSModalWindow.init('[data-modal-target]', {
        //     onOpen: function (e) {
        //         if (this[0].id == "realtorModal") {
        //             var vid1 = document.getElementById("realtorVideo");
        //             vid1.play();
        //         }
        //
        //         if (this[0].id == "apartmentModal") {
        //             var vid2 = document.getElementById("apartmentVideo");
        //             vid2.play();
        //         }
        //     },
        //     onClose: function (e) {
        //         var vid1 = document.getElementById("realtorVideo");
        //         vid1.pause();
        //
        //         var vid2 = document.getElementById("apartmentVideo");
        //         vid2.pause();
        //     },
        // });
        
        //if (GetIEVersion() > 0) {
		//	$('#apartmentModalBtn').click(function () {
		//		window.open("<?//= SITE_URL; ?>//apartment-video",'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=929,height=500');
		//	});
		//
		//	$('#realtorModalBtn').click(function () {
		//		window.open("<?//= SITE_URL; ?>//realtor-video",'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=929,height=500');
		//
		//	});
		//} else {
        
        $('#apartmentModalBtn').click(function () {
            var newModal = new Custombox.modal({
                content: {
                    target: '#apartmentModal',
                    effect: 'slit',
                    animateFrom: 'left',
                    animateTo: 'left',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                    onComplete: function () {
                        var vid = document.getElementById("apartmentVideo");
                        vid.play();
                    },
                    onClose: function () {
                        var vid = document.getElementById("apartmentVideo");
                        vid.pause();
                    }
                    
                    
                }
            });
            newModal.open();
        });
        
        $('#realtorModalBtn').click(function () {
            var newModal = new Custombox.modal({
                content: {
                    target: '#realtorModal',
                    effect: 'slit',
                    animateFrom: 'left',
                    animateTo: 'left',
                    positionX: 'center',
                    positionY: 'center',
                    speedIn: 300,
                    speedOut: 300,
                    fullscreen: false,
                    onComplete: function () {
                        var vid = document.getElementById("realtorVideo");
                        vid.play();
                    },
                    onClose: function () {
                        var vid = document.getElementById("realtorVideo");
                        vid.pause();
                    }
                    
                    
                }
            });
            newModal.open();
        });
		//}
        // initialization of carousel
        $.HSCore.components.HSCarousel.init('.js-carousel');
        
        // initialization of tabs
        $.HSCore.components.HSTabs.init('[role="tablist"]');
        
        // initialization of popups
        $.HSCore.components.HSPopup.init('.js-fancybox');
        
        // initialization of go to
        $.HSCore.components.HSGoTo.init('.js-go-to');
        
        // initialization of text animation (typing)
        $(".u-text-animation.u-text-animation--typing").typed({
            strings: [
                "an awesome template",
                "perfect template",
                "just like a boss"
            ],
            typeSpeed: 60,
            loop: true,
            backDelay: 1500
        });
        $('.select-plan').click(function (e) {
            e.preventDefault();
            localStorage.setItem("SelectedPlan", $(this).attr('id'));
            window.location.href = $(this).attr('href');
        });
        
        $('.3-icons').mouseover(function () {
            $(this).addClass('a fa-spin');
        });
        
        $('.3-icons').mouseout(function () {
            $(this).removeClass('a fa-spin');
        });
        
        function GetIEVersion() {
		  var sAgent = window.navigator.userAgent;
		  var Idx = sAgent.indexOf("MSIE");

		  // If IE, return version number.
		  if (Idx > 0)
			return parseInt(sAgent.substring(Idx+ 5, sAgent.indexOf(".", Idx)));

		  // If IE 11 then look for Updated user agent string.
		  else if (!!navigator.userAgent.match(/Trident\/7\./))
			return 11;

		  else
			return 0; //It is not IE
		}
    
    });
    
    $(window).on('load', function () {
        // initialization of header
        $.HSCore.components.HSHeader.init($('#js-header'));
        $.HSCore.helpers.HSHamburgers.init('.hamburger');
        
        // initialization of HSMegaMenu component
        $('.js-mega-menu').HSMegaMenu({
            event: 'hover',
            pageContainer: $('.container'),
            breakpoint: 991
        });
    });
    
    $(window).on('resize', function () {
        setTimeout(function () {
            $.HSCore.components.HSTabs.init('[role="tablist"]');
        }, 200);
    });
</script>
<!-- Demo modal window -->
<div id="apartmentModal" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="display: none; width: 929px; height: auto;">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Apartment</h4>
    <div calss="modal-body" id="imageMedia" style="position: relative;">
        <video controls="" width="100%" height="500" id="apartmentVideo">
            <source src="http://apartmentnetwork.com/animation/jillmy.mp4" type="video/mp4">
            <source src="http://apartmentnetwork.com/animation/jillmy.mp4" type="video/ogg">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="clear-both"></div>
</div>
<!-- End Demo modal window -->

<div id="realtorModal" class="text-left g-bg-white g-overflow-y-auto  g-pa-20"
     style="display: none; width: 929px; height: auto;">
    <button type="button" class="close" onclick="Custombox.modal.close();">
        <i class="hs-icon hs-icon-close"></i>
    </button>
    <h4 class="g-mb-20">Realtor</h4>
    <div calss="modal-body" id="imageMedia" style="position: relative;">
        <video controls="" width="100%" height="500" id="realtorVideo">
            <source src="http://apartmentnetwork.com/animation/barbara.mp4" type="video/mp4">
            <source src="http://apartmentnetwork.com/animation/barbara.mp4" type="video/ogg">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="clear-both"></div>
</div>

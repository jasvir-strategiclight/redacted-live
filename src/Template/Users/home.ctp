<!-- Begin SpeakPipe code -->
<script type="text/javascript">
(function(d){
    var app = d.createElement('script'); app.type = 'text/javascript'; app.async = true;
    var pt = ('https:' == document.location.protocol ? 'https://' : 'http://');
    app.src = pt + 'www.speakpipe.com/loader/01ftrrv47ni3u5ibxo3yj54vedszkb7z.js';
    var s = d.getElementsByTagName('script')[0]; s.parentNode.insertBefore(app, s);
})(document);
</script>
<!-- End SpeakPipe code -->
<style>
    label.error {
        color: #FFFFFF !important;
        font-family: 'Raleway',sans-serif;
    }
    
    .center-image {
    background-color: #282828;
    background-image: url(https://www.morninginvest.com/wp-content/uploads/2020/08/morning-invest-devices-final-1.png);
    background-repeat: no-repeat;
    background-size: 60%!important;
    background-position: 110% 5%!important;
    transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
    color: #FFFFFF;
    padding: 40px 40px 40px 40px;
}

.g-bg-white {
    background-color: #070707!important;
}

body {
    font-family: 'Ridley Grotesk';
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #292b2c;
    background-color: #090808 !important;
    overflow-x: hidden;
}
</style>
<!-- Promo Block -->
<section class="g-pos-rel center-image"
         style="">
    <div class="auto-init height-is-based-on-content use-loading mode-scroll">
        <div class="g-bg-cover__inner g-py-200">
            <div class="row pull-left ">
                <div class="col-lg-1 ">&nbsp;</div>
                <div class="col-lg-5" style="padding:20px;">
                    <form id="subscribeForm" action="javascript:void(0);">
                        <div class="row">
                            <div class="col-md-10">
                                <h1 class="">Join the mailing list</h1>
                                <h4 class="">Get the daily email that makes reading the investment news actually enjoyable.</h4>                            
                            </div>
                            <div class="col-md-8">
                                <input name="email" class="form-control" height="52px" id="emailId"
                                       placeholder="Enter your email" value="<?= $referredEmail; ?>">

                                <script src="https://www.google.com/recaptcha/api.js?render=6LfctbchAAAAAF7CDjfqxcyFP2mNlCTtt7qpU-XW"></script>
                                <script>
                                    grecaptcha.ready(function () {
                                        grecaptcha.execute('6LfctbchAAAAAF7CDjfqxcyFP2mNlCTtt7qpU-XW', {action: 'contact'}).then(function (token) {
                                            var recaptchaResponse = document.getElementById('recaptchaResponse');
                                            recaptchaResponse.value = token;
                                        });
                                    });
                                </script>
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-subscribe" id="subscribeBtn"><b>Subscribe</b></button>
                            </div>
                            <div class="col-md-10   ">
                                <br/>
                                <p>Stay <b>informed</b> and <b>ahead</b> in the game, for free.</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 ">&nbsp;</div>
            </div>
        </div>
    </div>
</section>
<!-- End Promo Block -->


<script>
    $(document).on('ready', function () {
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

        $('#subscribeForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: SITE_URL + "users/isUniqueEmail",
                },
            },
            messages: {
                email: {
                    required: "Please enter email.",
                    email: "Please enter valid email.",
                    remote: "Email already exists.",
                },
            },
            submitHandler: function (form) {
                $.ajax({
                    url: SITE_URL + 'users/wp-register',
                    type: "POST",
                    data: $('#subscribeForm').serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $('#subscribeBtn').html('Please wait...').attr('disabled', 'disabled');
                    },
                    success: function (response) {
                        window.location.href = 'https://redacted.inc/thank-you/';
                    }
                });
            }
        });

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

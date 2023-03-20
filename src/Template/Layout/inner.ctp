<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= SITE_TITLE ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    
    <?= $this->Html->css([
        ($this->request->getParam('action') == "home") ? 'bootstrap.min' : 'vendor/bootstrap/bootstrap.min',
        'vendor/icon-awesome/css/font-awesome.min',
        'vendor/icon-line/css/simple-line-icons',
        'vendor/icon-etlinefont/style',
        'vendor/icon-line-pro/style',
        'vendor/icon-hs/style',
        'vendor/dzsparallaxer/dzsparallaxer',
        'vendor/dzsparallaxer/dzsscroller/scroller',
        'vendor/dzsparallaxer/advancedscroller/plugin',
        'vendor/animate',
        'vendor/flatpickr/dist/css/flatpickr.min',
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/slick-carousel/slick/slick',
        'vendor/bootstrap-select/css/bootstrap-select.min',
        'vendor/typedjs/typed',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/hamburgers/hamburgers.min',
        'vendor/custombox/custombox.min',
        'unify-core',
        'unify-components',
        'unify-globals',
        'unify-admin',
        'vendor/app',
        'custom',
        'design',
    ]) ?>
    
    <script type="text/javascript">
        var SITE_URL = '<?= SITE_URL ?>';
    </script>
    
    <?= $this->Html->script([
        'vendor/jquery/jquery.min',
        'vendor/jquery-migrate/jquery-migrate.min',
        'vendor/popper.min',
        'vendor/bootstrap/bootstrap.min',
        'vendor/appear',
        'vendor/bootstrap-select/js/bootstrap-select.min',
        'vendor/malihu-scrollbar/jquery.mCustomScrollbar.concat.min',
        
        'vendor/cookiejs/jquery.cookie',
        'vendor/slick-carousel/slick/slick',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/dzsparallaxer/dzsparallaxer',
        'vendor/dzsparallaxer/dzsscroller/scroller',
        'vendor/dzsparallaxer/advancedscroller/plugin',
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/typedjs/typed.min',
        'vendor/custombox/custombox.legacy.min',
        'vendor/custombox/custombox.min',
        'vendor/flatpickr/dist/js/flatpickr.min',
        'hs.core.js',
        'components/hs.side-nav',
        'helpers/hs.hamburgers',
        'components/hs.carousel',
        'components/hs.header',
        'components/hs.range-datepicker',
        'components/hs.datepicker',
        'components/hs.tabs',
        'components/hs.popup',
        'components/text-animation/hs.text-slideshow',
        'components/hs.go-to',
        'components/hs.dropdown',
        'components/hs.modal-window',
        'components/hs.scrollbar',
        'jquery.validate.min',
        'jquery-input-mask-phone-number.min',
        'jquery.autocomplete',
        'jquery.tmpl',
        'popper.min',
        'custom',
    
    ]) ?>
    
    <?= $this->fetch('script') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143670412-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-143670412-1');
    </script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '213348850002766'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=213348850002766&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
</head>
<body>

<?= $this->Flash->render() ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->element('inner_header') ?>
        <div class="row py-1 g-pos-rel px-4" style="width: 100% !important;">
            <!-- ?= $this->element('account_sidebar') ? -->
            <div class="col-lg-12 g-my-30 px-3">
                <?= $this->fetch('content') ?>
            </div>
        </div>
        <?= $this->element('home_footer') ?>
    </div>
</div>
<?= $this->element('media') ?>
</body>
</html>
<script>
    $(document).on('ready', function () {
        // initialization of custom select
        
        setTimeout(function () {
            $('.message').fadeOut().remove();
        }, 4000);
        
    });
</script>

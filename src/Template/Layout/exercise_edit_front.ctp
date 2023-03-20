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
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/slick-carousel/slick/slick',
        'vendor/bootstrap-select/css/bootstrap-select.min',
        'vendor/typedjs/typed',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/hamburgers/hamburgers.min',
        'vendor/hs-admin-icons/hs-admin-icons',
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
        
        
        'vendor/slick-carousel/slick/slick',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/dzsparallaxer/dzsparallaxer',
        'vendor/dzsparallaxer/dzsscroller/scroller',
        'vendor/dzsparallaxer/advancedscroller/plugin',
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/typedjs/typed.min',
        'vendor/custombox/custombox.legacy.min',
        'vendor/custombox/custombox.min',
        
        'hs.core.js',
        'components/hs.carousel',
        'components/hs.header',
        'helpers/hs.hamburgers',
        'components/hs.tabs',
        'components/hs.popup',
        'components/text-animation/hs.text-slideshow',
        'components/hs.go-to',
        'components/hs.dropdown',
        'components/hs.modal-window',
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

</head>
<body>

<?= $this->Flash->render() ?>
<div class="row">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-10" style="width: 100%;">
        <?= $this->element('inner_header') ?>
        <div class="row py-5">
            <?php echo $this->element('account_sidebar') ?>
            <div class="col-lg-10 g-mb-50">
                <?php $this->Breadcrumb->create(); ?>
                <div class="row">
                    <div class="col-md-10">
                        <?= $this->fetch('content') ?>
                    </div>
                    <div class="col-md-2 g-mb-30 ">
                        <?= $this->element('exercise_edit_sidebar') ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?= $this->element('home_footer') ?>
    </div>
    <div class="col-md-1">&nbsp;</div>
</div>
<?= $this->element('media') ?>
</body>
</html>


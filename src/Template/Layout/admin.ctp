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
    
    <script type="text/javascript">
        var SITE_URL = '<?= SITE_URL ?>';
    </script>
    
    <?= $this->Html->css([
        'vendor/bootstrap/bootstrap.min',
        'vendor/icon-awesome/css/font-awesome.min',
        'vendor/icon-line/css/simple-line-icons',
        'vendor/icon-etlinefont/style',
        'vendor/icon-line-pro/style',
        'vendor/icon-hs/style',
        'vendor/hs-admin-icons/hs-admin-icons',
        
        'vendor/animate',
        'vendor/bootstrap-select/css/bootstrap-select.min',
        'vendor/multiselect/css/multi-select',
        'vendor/custombox/custombox.min',
        
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/hamburgers/hamburgers.min',
        'unify-admin',
        'vendor/app',
        'admin_custom',
    ]) ?>
    
    <?= $this->Html->script([
        'vendor/jquery/jquery.min',
        'vendor/jquery-migrate/jquery-migrate.min',
        'vendor/popper.min',
        'vendor/bootstrap/bootstrap.min',
        'vendor/cookiejs/jquery.cookie',
        
        'vendor/bootstrap-select/js/bootstrap-select.min',
        'vendor/malihu-scrollbar/jquery.mCustomScrollbar.concat.min',
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/multiselect/js/jquery.multi-select',
        'vendor/custombox/custombox.legacy.min',
        'vendor/custombox/custombox.min',
        
        'hs.core.js',
        'components/hs.side-nav',
        'helpers/hs.hamburgers',
        'components/hs.range-datepicker',
        'components/hs.datepicker',
        'components/hs.dropdown',
        'components/hs.scrollbar',
        'components/hs.modal-window',
        'helpers/hs.focus-state',
        'components/hs.popup',
        'jquery.validate.min',
        'jquery.autocomplete',
        'jquery-input-mask-phone-number.min',
        'jquery.tmpl',
        'admin_custom',
    
    ]) ?>
    
    
    <?= $this->fetch('script') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    
    <style type="text/css">
        .message {
            position: fixed;
            top: 20px;
            text-align: center;
            color: #ffffff;
            z-index: 9999;
            width: 80%;
            margin: 0 10%;
        }

    </style>

</head>
<body>
    <?php echo $this->element('Admin/admin_header') ?>
    <main class="container-fluid px-0 g-pt-65">
        <div class="row no-gutters g-pos-rel g-overflow-x-hidden">
            <?= $this->element('Admin/admin_sidebar') ?>
            <?= $this->Flash->render() ?>
            <div class="col g-ml-45 g-ml-0--lg g-pb-65--md">
                <section class="g-pa-10">
                        <?php $this->Breadcrumb->create(); ?>
                        <?= $this->fetch('content') ?>
                </section>
                <?= $this->element('Admin/admin_footer') ?>
            </div>
        </div>
    </main>
<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // initialization of custom select
        
        // initialization of hamburger
        $.HSCore.helpers.HSHamburgers.init('.hamburger');
        
        // initialization of sidebar navigation component
        $.HSCore.components.HSSideNav.init('.js-side-nav', {
            afterOpen: function () {
                //Do Somthing
            },
            afterClose: function () {
                //Do Somthing
            }
        });


        
        
        // initialization of HSDropdown component
        $.HSCore.components.HSDropdown.init($('[data-dropdown-target]'), {dropdownHideOnScroll: false});
        
        // initialization of custom scrollbar
        $.HSCore.components.HSScrollBar.init($('.js-custom-scroll'));
        
        setTimeout(function () {
            $('.message').fadeOut().remove();
        }, 4000);
        
    });
</script>
    <?= $this->element('Admin/media') ?>
</body>
</html>

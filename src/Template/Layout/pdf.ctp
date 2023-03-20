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
        //'unify-admin',
        'vendor/app',
        'custom',
        'design',
    ]) ?>
    <?= $this->fetch('script') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>
<body>
<?= $this->fetch('content') ?>
</body>
</html>

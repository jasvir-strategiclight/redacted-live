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
        'vendor/bootstrap/bootstrap.min',
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
        'vendor/typedjs/typed',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/hamburgers/hamburgers.min',
        'vendor/hs-admin-icons/hs-admin-icons',
        'unify-core',
        'unify-components',
        'unify-admin',
        'unify-globals',
        'custom',
    ]) ?>

    <?= $this->Html->script([
        'vendor/jquery/jquery.min',
        'vendor/jquery-migrate/jquery-migrate.min',
        'vendor/popper.min',
        'vendor/bootstrap/bootstrap.min',

        'vendor/slick-carousel/slick/slick',
        'vendor/hs-megamenu/src/hs.megamenu',
        'vendor/dzsparallaxer/dzsparallaxer',
        'vendor/dzsparallaxer/dzsscroller/scroller',
        'vendor/dzsparallaxer/advancedscroller/plugin',
        'vendor/fancybox/jquery.fancybox.min',
        'vendor/typedjs/typed.min',

        'hs.core.js',
        'components/hs.carousel',
        'components/hs.header',
        'helpers/hs.hamburgers',
        'components/hs.tabs',
        'components/hs.popup',
        'components/text-animation/hs.text-slideshow',
        'components/hs.go-to',
        'custom',

    ]) ?>

    <?= $this->fetch('script') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>
<body>
<main>
    <?php $this->element('Admin/admin_login_header') ?>
    <?= $this->fetch('content') ?>
    <?php $this->element('Admin/admin_login_footer') ?>
</main>


</body>
</html>

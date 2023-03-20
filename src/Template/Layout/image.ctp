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
        'unify-core',
        'unify-components',
        'unify-globals',
        'unify-admin',
        'vendor/app',
        'custom',
    ]) ?>
    
    <script type="text/javascript">
        var SITE_URL = '<?= SITE_URL ?>';
    </script>
    
    <?= $this->Html->script([
        'vendor/jquery/jquery.min',
        'vendor/jquery-migrate/jquery-migrate.min',
        'vendor/popper.min',
        'vendor/bootstrap/bootstrap.min',
        'hs.core.js',
        'components/hs.carousel',
        'components/hs.header',
        'jquery.validate.min',
        'custom',
    ]) ?>
    
    <?= $this->fetch('script') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>
<body>
<main>
    <?= $this->fetch('content') ?>
</main>
</body>
</html>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">

    <title><?= lang('Errors.whoops') ?></title>

    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
    </style>
</head>
<body>

    <div class="container text-center"><h1 class="headline"><?= lang('Errors.whoops') ?></h1>
        <div class="page-title-wrap">
          <h1 class="headline"><?= lang('Errors.whoops') ?></h1>
          <img src="/assets/images/Pattern simple - 1.png" alt="Pattern" class="header-pattern-img" />
        </div>

        <p class="lead"><?= lang('Errors.weHitASnag') ?></p>

    </div>

</body>

</html>

<?php

/**
 * @app/view/layout/main.php
 *
 * @package     @app/view/layout/main.php
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        6/18/18 10:34 AM
 * @version     1.0
 */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\models\queries\Bitacora;
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body">


<?php

$this->beginBody();

if (Yii::$app->user->isGuest) {
    echo Yii::$app->view->render('@app/views/partials/_menuGuest');
} else {
    echo Yii::$app->view->render('@app/views/partials/_menuAdmin');
}

try {
    echo Breadcrumbs::widget([
        'links' => isset($this->params[BREADCRUMBS]) ? $this->params[BREADCRUMBS] : [],
    ]);

} catch(Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'views\layout\main::Breadcrumbs', MSG_ERROR);
}

echo '<div class="webpage">';
try {
    echo Alert::widget();
} catch(Exception $exception) {
    $bitacora = new Bitacora();
    $bitacora->register($exception, 'views\layout\main::Alert', MSG_ERROR);
}
echo '</div>';

echo '<div class="webpage">';
echo $content;
echo '</div>';

echo Yii::$app->view->render('@app/views/partials/_footer');

$this->endBody();

?>


</body>
</html>
<?php $this->endPage() ?>

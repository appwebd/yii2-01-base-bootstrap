<?php
/**
 * Layout
 * PHP version 7.2.0
 *
 * @category  View
 * @package   Layout
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
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
<html lang="<?php echo  Yii::$app->language ?>">
<head>
    <meta charset="<?php echo  Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?php echo  Html::encode($this->title) ?></title>
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
    echo Breadcrumbs::widget(
        [
        'links' => isset($this->params[BREADCRUMBS]) ? 
            $this->params[BREADCRUMBS] : [],
        ]
    );

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
echo HTML_DIV_CLOSE;

echo '<div class="webpage">';
echo $content;
echo HTML_DIV_CLOSE;

echo Yii::$app->view->render('@app/views/partials/_footer');

$this->endBody();

?>
</body>
</html>
<?php $this->endPage() ?>

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

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache" >

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

    echo Breadcrumbs::widget([
        'links' => isset($this->params[BREADCRUMBS]) ? $this->params[BREADCRUMBS] : [],
    ]);

    echo '<div class="webpage">';
        echo Alert::widget() ;
    echo '</div>';
    
    echo '<div class="webpage">';
    echo $content;
    echo '</div>';
    
    echo Yii::$app->view->render('@app/views/partials/_footer');

    $this->endBody() ;

    ?>


</body>
</html>
<?php $this->endPage() ?>

<?php
/**
 * User
 *
 * @package     Update of User
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 23:03:07
 * @version     1.0
 */

use app\components\UiComponent;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */


$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Update');

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->header(
    'user',
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    )
);

echo $this->renderFile('@app/views/user/_form.php', ['model'=>$model]);
echo HTML_WEBPAGE_CLOSE;

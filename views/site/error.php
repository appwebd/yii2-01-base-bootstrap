<?php
/**
  * Error view handling
  *
  * @package     Error view handling
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use yii\helpers\Html;
use app\controllers\BaseController;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = Yii::t('app', 'Error');
$this->params[BREADCRUMBS][] = $this->title;

$error = nl2br(Html::encode($message . ' url: ' . Yii::$app->request->url));
BaseController::bitacora(Yii::t('app', 'Error: {error}', ['error' => $error]), MSG_ERROR);
?>

<div class="webpage ">
    <div class="row">
        <div class="col-sm-12 box">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>
        <div class="danger error-summary">
            <strong>
                <?= nl2br(Html::encode($message)) ?>
            </strong>
        </div>
        <br>
        <p>
            <?= Yii::t(
                    'app',
                    'The above error occurred while the Web server was processing your request. 
                    We are generating a record status of this error. Thank you.'
            );?>
        </p>
        <br>

        </div>
    </div>
</div>

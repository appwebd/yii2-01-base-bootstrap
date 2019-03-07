<?php
/**
 * Signed Up message information view
 *
 * @package     Signed Up message information view
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-06-16 16:49:58
 * @version     1.0
 */

use \app\components\UiComponent;

/* @var yii\web\View $this */
/* @var app\models\User $model */

$this->title = Yii::t('app', 'Signed Up');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">

            <div class=" ">';
                echo UiComponent::header(
                    'user',
                    Yii::t(
                        'app',
                        'Thanks for your registration'
                    ),
                    ' '
                );
                echo '                
                <p>';
                echo Yii::t(
                    'app',
                    'We have sent an email with a link for your confirmation, please check your inbox'
                );
                echo '</p>';
                echo Yii::$app->view->render('@app/views/partials/_links_return_to');
                echo '
            </div>
        </div>
        <div class="col-sm-3 "> &nbsp; </div>
    </div>
</div>';

<?php
/**
  * Profiles
  *
  * @package     Create of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', 'Create');

echo '
<div class="webpage">
    <div class="row">
        <div class="col-sm-12 box">';

            echo Yii::$app->ui->header(
                'globe',
                $this->title,
                Yii::t(
                    'app',
                    'Please complete all requested information.'
                )
            );

            echo $this->render('_form', ['model' => $model,]);
            echo '
        </div>
    </div>
</div>';

<?php
/**
  * Profiles
  *
  * @package     View of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 19:28:34
  * @version     1.0
*/

use yii\widgets\DetailView;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = $model->profile_id;

echo HTML_WEBPAGE_OPEN;

echo Yii::$app->ui->header(
    'globe',
    $this->title,
    Yii::t('app', 'This view permit view detailed information of Profiles')
);

echo DetailView::widget([
  'model' => $model,
  'attributes' => [
    'profile_id',
    'profile_name',
    'created_at',
    'updated_at',
      [
          ATTRIBUTE => 'active',
          OPTIONS => [STR_CLASS=>'col-sm-1'],
          VALUE => function ($model) {
              return Yii::$app->ui->yesOrNo($model->active);
          },
          FORMAT=>'raw'
      ],
    ],
]);

echo Yii::$app->ui->buttonsViewBottom($model);

echo HTML_WEBPAGE_CLOSE;

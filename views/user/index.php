<?php
/**
  * User
  *
  * @package     Index of user
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-07-30 14:27:11
  * @version     1.0
*/

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Company;
use app\models\Profile;
use app\models\User;
use app\models\search\CompanySearch;
use app\models\search\ProfileSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProyProyectosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', User::TITLE);

$this->params[BREADCRUMBS][] = $this->title;

echo Yii::$app->ui::HTML_WEBPAGE_OPEN;

echo Html::beginForm(['user/index'], 'post');
echo Yii::$app->ui->headerAdmin(
    'user',
    $this->title,
    Yii::t('app', 'This view permit Create a new User, update or delete information related of user')
);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout'=>'{items}{summary}{pager}',
    'filterSelector' => 'select[name="per-page"]',
    'tableOptions' =>[STR_CLASS => GRIDVIEW_CSS],
    'columns' => [

        [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS=>'width:10px'] ],

        'username',
        'firstName',
        'lastName',
        'email',

        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => 'company_id',
            FILTER => CompanySearch::getCompanyListSearch('user'),
            VALUE => function ($model) {
                return Company::getCompanyName($model->company_id);
            }
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            ATTRIBUTE => 'profile_id',
            FILTER => ProfileSearch::getProfileListSearch('user'),
            VALUE => function ($model) {
                $profile_name = Profile::getProfileName($model->profile_id);
                return Yii::$app->ui->badgetStatus($model->profile_id, $profile_name);
            },
            FORMAT => 'raw',
        ],
        [
            STR_CLASS => yii\grid\DataColumn::className(),
            FILTER => Yii::$app->ui->yesOrNoArray(),
            ATTRIBUTE => 'active',
            OPTIONS => [STR_CLASS=>'col-sm-1'],
            VALUE => function ($model) {
                return Yii::$app->ui->yesOrNo($model->active);
            },
            FORMAT=>'raw'
        ],
        [
            STR_CLASS => 'yii\grid\ActionColumn',
            'header'=> Yii::$app->ui->pageSizeDropDownList($pageSize),
            'template'=>'{view} {update} {delete}',
            'contentOptions'=>[STR_CLASS=>'GridView'],
        ],
    ],
]);

echo '<br/><br/>';
echo Yii::$app->ui->buttonsAdminBottom();
Html::endForm();
echo Yii::$app->ui::HTML_WEBPAGE_CLOSE;

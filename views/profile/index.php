<?php
/**
  * Profiles
  *
  * @package     Index of Profile
  * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
  * @copyright   (C) Copyright - Web Application development
  * @license     Private comercial license
  * @link        https://appwebd.github.io
  * @date        2018-08-26 17:15:29
  * @version     1.0
*/

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\queries\Common;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$template = Common::getProfilePermissionString();
$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="webpage">
    <div class="row">
        <div class="col-sm-12 box">';

        echo Html::beginForm(['profile/index'], 'post');
        echo Yii::$app->ui->headerAdmin(
            'user',
            $this->title,
            Yii::t('app', 'This view permit Create a new User, update or delete information related of user'),
            'profile',
            true,
            false
        );

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout'=>'{items}{summary}{pager}',
            'filterSelector' => 'select[name="per-page"]',
            'tableOptions' =>[STR_CLASS => 'table maxwidth items table-striped table-condensed'],
            'columns' => [
                [STR_CLASS => 'yii\grid\CheckboxColumn', 'options'=>[STR_CLASS => 'width10px']],
                'profile_name',
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
                    HEADER => Yii::$app->ui->pageSizeDropDownList($pageSize),
                    'template' => $template,
                    'contentOptions' => [STR_CLASS => 'GridView'],
                ]
            ]
        ]);

        echo '<br/><br/>';
        echo Yii::$app->ui->buttonsAdminBottom();
        Html::endForm();

        echo '
        </div>
    </div>
</div>';

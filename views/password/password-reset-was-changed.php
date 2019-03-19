<?php

use app\components\UiComponent;

/* @var yii\web\View $this */
/* @var \app\models\User $model */

$this->title = Yii::t('app', 'Password was updated');
$this->params[BREADCRUMBS][] = $this->title;

echo '
<div class="container ">
    <div class="row">
        <div class="col-sm-3 "> &nbsp; </div>
        <div class="col-sm-6 box">';

echo UiComponent::header('lock', Yii::t('app', 'Password'), Yii::t(
    'app',
    'The password used at this platform was updated successfully'
));
echo '<div class="success"><h4>',
Yii::t('app', 'Password was updated'),
'<br/><br/><br/></h4></div>';

echo Yii::$app->view->render('@app/views/partials/_links_return_to');

echo '
        </div>
        <div class="col-sm-3 "> &nbsp; </div>
    </div>
</div>';

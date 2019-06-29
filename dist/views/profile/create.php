<?php
/**
 * Profile Create/Update
 * PHP version 7.0.0
 *
 * @category  View
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */

use app\components\UiComponent;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var object $model app\models\Profile */
/* @var string $titleView Title view */

$this->title = Yii::t('app', Profile::TITLE);
$this->params[BREADCRUMBS][] = ['label' => $this->title, 'url' => ['index']];
$this->params[BREADCRUMBS][] = Yii::t('app', $titleView);

echo HTML_WEBPAGE_OPEN;

$uiComponent = new UiComponent();
$uiComponent->header(
    Profile::ICON,
    $this->title,
    Yii::t(
        'app',
        'Please complete all requested information.'
    )
);

echo $this->renderFile('@app/views/profile/_form.php', ['model' => $model]);
echo HTML_WEBPAGE_CLOSE;

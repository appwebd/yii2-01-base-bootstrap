<?php

namespace app\components;


use app\controllers\BaseController;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Class UiButtons
 *
 * @package  Components
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @category Category
 * @license  Private license
 * @link     https://appwebd.github.io
 */
class UiButtons extends Component
{
    const BUTTON_ICON_BACK_INDEX = '<span class="glyphicon glyphicon-list">
                                    </span>&nbsp;';
    const BUTTON_ICON_DELETE = '<span class="glyphicon glyphicon-trash">
                               </span>&nbsp;';
    const BUTTON_ICON_CREATE = '<span class="glyphicon glyphicon-plus">
                               </span>&nbsp;';
    const BUTTON_ICON_REFRESH = '<span class="glyphicon glyphicon-refresh"> 
                                </span>&nbsp;';
    const BUTTON_ICON_UPDATE = '<span class="glyphicon glyphicon-pencil">
                                </span>&nbsp;';
    const BUTTON_ICON_SAVE = '<span class="glyphicon glyphicon-save"></span>&nbsp;';
    const BUTTON_TEXT_BACK_INDEX = 'Back to admin list';
    const BUTTON_TEXT_CREATE = 'New';
    const BUTTON_TEXT_DELETE = 'Delete';
    const BUTTON_TEXT_REFRESH = 'Refresh';
    const BUTTON_TEXT_REFRESH_FORM = 'Refresh';
    const BUTTON_TEXT_UPDATE = 'Update';
    const BUTTON_TEXT_SAVE = 'Save';
    const BUTTON_TEXT_TOOLTIP = 'Create a new record';
    const ACTION_VIEW_ICON = '<span class="glyphicon glyphicon-eye-open"></span>';
    const ACTION_UPDATE_ICON = '<span class="glyphicon glyphicon-pencil"></span>';
    const ACTION_DELETE_ICON = '<span class="glyphicon glyphicon-trash"></span>';

    const CSS_BTN_DEFAULT = 'btn btn-default';
    const CSS_BTN_PRIMARY = 'btn btn-primary';
    const CSS_BTN_DANGER = 'btn btn-danger';
    const HTML_TOOLTIP = 'tooltip';
    const HTML_DATA_TOGGLE = 'data-toggle';
    const HTML_DATA_PLACEMENT = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE = 'top';
    const HTML_TITLE = 'title';
    const HTML_SPACE = '&nbsp;';
    const STR_CLASS_DATA_PJAX = 'data-pjax';
    const STR_CONFIRM = 'confirm';

    /**
     * Get Buttons action for action in gridview
     *
     * @param string $icon  Icon style for example glyphicon glyphicon-eye-open
     * @param string $url   Url
     * @param string $key   key encoded
     * @param string $title title of links
     *
     * @return string
     */
    public static function getUrlButtonAction($icon, $url, $key, $title)
    {
        $url = Yii::$app->controller->id . $url;
        return Html::a(
            $icon,
            [
                $url,
                'id' => BaseController::stringEncode($key)
            ],
            [
                TITLE => Yii::t('app', $title),
                self::STR_CLASS_DATA_PJAX => '0',
            ]
        );
    }

    /**
     * Show icon action column in grid view Widget
     *
     * @return array
     */
    public static function buttonsActionColumn()
    {

        return [
            ACTION_VIEW => function ($url, $model, $key) {
                return UiButtons::getUrlButtonAction(
                    self::ACTION_VIEW_ICON, '/view', $key, 'Show more details'
                );
            },
            ACTION_UPDATE => function ($url, $model, $key) {
                return UiButtons::getUrlButtonAction(
                    self::ACTION_UPDATE_ICON, '/update', $key,'Update'
                );
            },
            ACTION_DELETE => function ($url, $model, $key) {

                $key = BaseController::stringEncode($key);
                return Html::a(
                    self::ACTION_DELETE_ICON,
                    [
                        Yii::$app->controller->id . '/delete',
                        'id' => $key
                    ],
                    [
                        TITLE => Yii::t('app', 'Delete record'),
                        self::STR_CLASS_DATA_PJAX => '0',
                        'data-confirm' => Yii::t(
                            'yii',
                            'Are you sure you want to delete?'
                        ),
                        'data-method' => 'post',
                    ]
                );
            }
        ];
    }

    /**
     * Show actions with buttons
     *
     * @param object $model mixed
     *
     * @return void
     */
    public  function buttonsViewBottom(&$model)
    {
        $primaryKey = $model->getId();
        $primaryKey = BaseController::stringEncode($primaryKey);

        $buttonCreate = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $buttonCreate = $this->button(
                self::BUTTON_ICON_CREATE . Yii::t('app', self::BUTTON_TEXT_CREATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                [ACTION_CREATE]
            );
        }

        $buttonDelete = '';
        if (Common::getProfilePermission(ACTION_DELETE)) {
            $buttonDelete = $this->buttonDelete(
                [ACTION_DELETE, 'id' => $primaryKey],
                self::CSS_BTN_DANGER
            );
        }

        $buttonUpdate = '';
        if (Common::getProfilePermission(ACTION_UPDATE)) {
            $buttonUpdate = $this->button(
                self::BUTTON_ICON_UPDATE . Yii::t('app', self::BUTTON_TEXT_UPDATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Update the current record'),
                [ACTION_UPDATE, 'id' => $primaryKey]
            );
        }

        echo '<br>',
        $buttonCreate,
        self::HTML_SPACE,
        $buttonUpdate,
        self::HTML_SPACE,
        $buttonDelete,
        self::HTML_SPACE,
        $this->button(
            self::BUTTON_ICON_BACK_INDEX . Yii::t(
                'app',
                self::BUTTON_TEXT_BACK_INDEX
            ),
            self::CSS_BTN_PRIMARY,
            Yii::t('app', 'Back to administration view'),
            [ACTION_INDEX]
        );
    }

    /**
     * Show a button in view
     *
     * @param string $caption       string caption of button
     * @param string $css           string style of button
     * @param string $buttonToolTip string help tooltip
     * @param array  $aAction       array of string with action to do
     *
     * @return string
     */
    public function button($caption, $css, $buttonToolTip, $aAction = [])
    {

        return Html::a(
            $caption,
            $aAction,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => $buttonToolTip,
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }

    /**
     * Show button delete in view
     *
     * @param array  $action array action
     * @param string $css    string style
     *
     * @return string
     */
    public function buttonDelete($action, $css)
    {
        return Html::a(
            self::BUTTON_ICON_DELETE . Yii::t('app', self::BUTTON_TEXT_DELETE),
            $action,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => Yii::t('app', 'Delete the selected records'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                'data' => [
                    self::STR_CONFIRM => Yii::t(
                        'app',
                        'Are you sure you want to delete this item?'
                    ),
                    METHOD => 'post',
                ]
            ]
        );
    }

    /**
     * Buttons in view create
     *
     * @param int  $tabIndex        nro secuence
     * @param bool $showBackToIndex Visible or not button "back to index list"
     *
     * @return void
     */
    public function buttonsCreate($tabIndex, $showBackToIndex = true)
    {
        $buttonSave = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $buttonSave = $this->buttonSave($tabIndex);
        }

        echo $buttonSave,
        self::HTML_SPACE,
        '<button type=\'reset\' class=\'', self::CSS_BTN_DEFAULT, '\' ',
        self::HTML_TITLE, '=\'', Yii::t('app', self::BUTTON_TEXT_REFRESH), '\' ',
        self::HTML_DATA_TOGGLE, '=\'', self::HTML_TOOLTIP, '\' ',
        self::HTML_DATA_PLACEMENT, '=\'', self::HTML_DATA_PLACEMENT_VALUE,
        '\'>', self::BUTTON_ICON_REFRESH , Yii::t(
            'app',
            self::BUTTON_TEXT_REFRESH
        ), '</button>',
        self::HTML_SPACE;
        if ($showBackToIndex) {
            echo $this->button(
                self::BUTTON_ICON_BACK_INDEX . Yii::t(
                    'app',
                    self::BUTTON_TEXT_BACK_INDEX
                ),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
        }
    }

    /**
     * Return html for button save
     *
     * @param int $tabIndex Nro. secuense order in tab forms.
     *
     * @return string declaration of buttonSave
     */
    public function buttonSave($tabIndex)
    {
        return Html::submitButton(
            self::BUTTON_ICON_SAVE . Yii::t('app', self::BUTTON_TEXT_SAVE),
            [
                STR_CLASS => self::CSS_BTN_PRIMARY,
                self::HTML_TITLE => Yii::t(
                    'app',
                    'Save the information of this form'
                ),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                'name' => 'save-button',
                'id' => self::BUTTON_TEXT_SAVE,
                VALUE => 'save-button',
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => $tabIndex,
            ]
        );
    }

    /**
     * Buttons in admin view
     *
     * @param string $showButtons  String with boolean values to show
     *                             Create, refresh, delete buttons.
     * @param bool   $buttonHeader boolean indicator visible or not visible
     *                             button header
     *
     * @return void
     */
    public function buttonsAdmin($showButtons = '111', $buttonHeader = true)
    {
        try {
            $showButtons = str_split($showButtons, 1);

            $buttonCreate = '';
            if ($showButtons[0] && Common::getProfilePermission(ACTION_CREATE)) {
                $buttonCreate = $this->button(
                    self::BUTTON_ICON_CREATE . Yii::t(
                        'app',
                        self::BUTTON_TEXT_CREATE
                    ),
                    self::CSS_BTN_PRIMARY,
                    Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                    [ACTION_CREATE]
                );
            }

            $buttonDelete = '';
            if ($showButtons[2] && Common::getProfilePermission(ACTION_DELETE)) {
                $buttonDelete = $this->buttonDelete(
                    [ACTION_REMOVE],
                    self::CSS_BTN_DEFAULT
                );
            }

            $buttonRefresh = '';
            if ($showButtons[1]) {
                $buttonRefresh = $this->buttonRefresh();
            }

            if ($buttonHeader) {
                echo '<br/>', $buttonCreate, self::HTML_SPACE,
                $buttonRefresh, self::HTML_SPACE, $buttonDelete;
            } else {
                echo '<br/><br/><br/>', $buttonDelete, self::HTML_SPACE,
                $buttonRefresh, self::HTML_SPACE, $buttonCreate;
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register($exception, 'buttonsAdmin', MSG_ERROR);
        }
    }

    /**
     * Button Refresh view
     *
     * @param string $caption Caption of button
     *
     * @return string
     */
    public function buttonRefresh($caption = self::BUTTON_TEXT_REFRESH)
    {
        $caption = self::BUTTON_ICON_REFRESH . Yii::t('app', $caption);
        return Html::a(
            $caption,
            [ACTION_INDEX],
            [
                STR_CLASS => self::CSS_BTN_DEFAULT,
                self::HTML_TITLE => Yii::t('app', 'Refresh view'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }

}

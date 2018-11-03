<?php
/**
 * Class UiComponent
 *
 * @package     Ui
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        6/28/18 2:33 PM
 * @version     1.0
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use app\models\queries\Common;
use app\models\Status;

/**
 * Class UiComponent
 *
 * @package     Ui
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        11/1/18 11:01 AM
 * @version     1.0
 */
class UiComponent extends Component
{

    const BOOL_TEXT                     = true;
    const BOOL_ACTIONS_TEXT             = false;
    const BOOL_TOOLTIP                  = true;
    const BOOL_ACTION_TEXT              = false;
    const BOOL_ACTION_TOOLTIP           = false;
    const BUTTON_ICON_BACK_INDEX        = '<span class="glyphicon glyphicon-list"></span>&nbsp;';
    const BUTTON_ICON_DELETE            = '<span class="glyphicon glyphicon-trash"></span>&nbsp;';
    const BUTTON_ICON_CREATE            = '<span class="glyphicon glyphicon-plus"></span>&nbsp;';
    const BUTTON_ICON_REFRESH           = '<span class="glyphicon glyphicon-refresh"></span>&nbsp;';
    const BUTTON_ICON_UPDATE            = '<span class="glyphicon glyphicon-pencil"></span>&nbsp;';
    const BUTTON_ICON_SAVE              = '<span class="glyphicon glyphicon-save"></span>&nbsp;';
    const BUTTON_TEXT_BACK_INDEX        = 'Back to admin list';
    const BUTTON_TEXT_CREATE            = 'New';
    const BUTTON_TEXT_DELETE            = 'Delete';
    const BUTTON_TEXT_REFRESH           = 'Refresh';
    const BUTTON_TEXT_REFRESH_FORM      = 'Refresh';
    const BUTTON_TEXT_UPDATE            = 'Update';
    const BUTTON_TEXT_SAVE              = 'Save';
    const BUTTON_TEXT_TOOLTIP           = 'Create a new record';
    const COLOR_PRIMARY                 = ' text-primary '; // add one space blank before and after the color
    const CSS_ICON_COLOR                = "slategray";
    const CSS_BTN_DEFAULT               = 'btn btn-default';
    const CSS_BTN_PRIMARY               = 'btn btn-primary';
    const CSS_BTN_DANGER                = 'btn btn-danger';
    const HTML_TOOLTIP                  = 'tooltip';
    const HTML_DATA_TOGGLE              = 'data-toggle';
    const HTML_DATA_PLACEMENT           = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE     = 'top';
    const HTML_TITLE                    = 'title';
    const HTML_SPACE                    = '&nbsp;';
    const HTML_OPTION                   = '<option>';
    const HTML_OPTION_CLOSE             = '</option>';
    const STR_PER_PAGE                  = 'per-page';
    const STR_PAGESIZE                  = 'pageSize';
    const STR_CONFIRM                   = 'confirm';

    /**
     * @param $statusId
     * @param $status
     * @return string
     */
    public static function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);
        return '<span class="badge badge-'.$badge.'">'. $status . '</span>';
    }

    /**
     * @param $caption string caption of button
     * @param $css     string style of button
     * @param $buttonToolTip  string help tooltip
     * @param array $aAction  array of string with action to do
     * @return string
     */
    public static function button($caption, $css, $buttonToolTip, $aAction = [])
    {

        return Html::a(
            $caption,
            $aAction,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => $buttonToolTip,
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT =>self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }

    /**
     * @param $showButtons String with boolean values to show Create, refresh, delete buttons.
     * @param $button_header boolean Show header in view ? (true/false value)
     * @return void
     */
    public static function buttonsAdmin($showButtons = '111', $button_header = true)
    {
        $show_buttons = str_split($showButtons, 1);

        $button_create = '';
        if ($show_buttons[0] && Common::getProfilePermission(ACTION_CREATE)) {
            $button_create = UiComponent::button(
                self::BUTTON_ICON_CREATE. Yii::t('app', self::BUTTON_TEXT_CREATE),
                self::CSS_BTN_PRIMARY,
                Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                [ACTION_CREATE]
            );
        }

        $button_delete = '';
        if ($show_buttons[2] && Common::getProfilePermission(ACTION_DELETE)) {
            $button_delete= UiComponent::buttonDelete(
                [ACTION_REMOVE],
                self::CSS_BTN_DEFAULT
            );
        }

        $button_refresh = '';
        if ($show_buttons[1]) {
            $button_refresh = UiComponent::buttonRefresh();
        }

        if ($button_header) {
            echo '<br/>',
            $button_create,
            self::HTML_SPACE,
            $button_refresh,
            self::HTML_SPACE,
            $button_delete;
        } else {
            echo '<br/><br/><br/>',
            $button_delete,
            self::HTML_SPACE,
            $button_refresh,
            self::HTML_SPACE,
            $button_create;
        }
    }

    /**
     * Show actions with buttons
     *
     * @param $model mixed
     */

    public static function buttonsViewBottom(&$model)
    {
        $primary_key = $model->getId();
        $button_create = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $button_create = UiComponent::button(
                self::BUTTON_ICON_CREATE. Yii::t('app', self::BUTTON_TEXT_CREATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                [ACTION_CREATE]
            );
        }

        $button_delete = '';
        if (Common::getProfilePermission(ACTION_DELETE)) {
            $button_delete= UiComponent::buttonDelete(
                [ACTION_DELETE, 'id' => $primary_key],
                self::CSS_BTN_DANGER
            );
        }

        $button_update= '';
        if (Common::getProfilePermission(ACTION_UPDATE)) {
            $button_update = UiComponent::button(
                self::BUTTON_ICON_UPDATE . Yii::t('app', self::BUTTON_TEXT_UPDATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Update the current record'),
                [ACTION_UPDATE, 'id' => $primary_key]
            );
        }

        echo $button_create,
            self::HTML_SPACE,
            $button_update,
            self::HTML_SPACE,
            $button_delete,
            self::HTML_SPACE,
            UiComponent::button(
                self::BUTTON_ICON_BACK_INDEX . Yii::t('app', self::BUTTON_TEXT_BACK_INDEX),
                self::CSS_BTN_PRIMARY,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
    }

    /**
     * @param $tabIndex
     * @param bool $showBackToIndex
     */
    public static function buttonsCreate($tabIndex, $showBackToIndex = true)
    {
        $button_save = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $button_save = UiComponent::buttonSave($tabIndex);
        }

        echo $button_save,
        self::HTML_SPACE,
        '<button type=\'reset\' class=\'', self::CSS_BTN_DEFAULT,'\' ',
                self::HTML_TITLE,'=\'', Yii::t('app', self::BUTTON_TEXT_REFRESH),'\' ',
                self::HTML_DATA_TOGGLE,'=\'',self::HTML_TOOLTIP,'\' ',
                self::HTML_DATA_PLACEMENT,'=\'',self::HTML_DATA_PLACEMENT_VALUE,
        '\'>',self::BUTTON_ICON_REFRESH . Yii::t('app', self::BUTTON_TEXT_REFRESH),'</button>',

        self::HTML_SPACE;
        if ($showBackToIndex) {
            echo UiComponent::button(
                self::BUTTON_ICON_BACK_INDEX . Yii::t('app', self::BUTTON_TEXT_BACK_INDEX),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
        }
    }

    /**
     * @param $action string action
     * @param $css    string style
     * @return string
     */
    public static function buttonDelete($action, $css)
    {
        return Html::a(
            self::BUTTON_ICON_DELETE . Yii::t('app', self::BUTTON_TEXT_DELETE),
            $action,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => Yii::t('app', 'Delete the selected records'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT =>self::HTML_DATA_PLACEMENT_VALUE,
                'data' => [
                    self::STR_CONFIRM => Yii::t('app', 'Are you sure you want to delete this item?'),
                    METHOD => 'post',
                ]
            ]
        );
    }

    /**
     * Button Refresh view
     * @param string $caption
     * @return string
     */
    public static function buttonRefresh($caption = self::BUTTON_TEXT_REFRESH)
    {
        $caption = self::BUTTON_ICON_REFRESH . Yii::t('app', $caption);
        return Html::a(
            $caption,
            [ACTION_INDEX],
            [
                STR_CLASS => self::CSS_BTN_DEFAULT,
                self::HTML_TITLE => Yii::t('app', 'Refresh view'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT =>self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }
    /**
     * Return html for button save
     *
     * @param integer $tabIndex
     * @return string declaration of buttonSave
     */
    public static function buttonSave($tabIndex)
    {
        return  Html::submitButton(
            self::BUTTON_ICON_SAVE . Yii::t('app', self::BUTTON_TEXT_SAVE),
            [
                STR_CLASS => self::CSS_BTN_PRIMARY,
                self::HTML_TITLE => Yii::t('app', 'Save the information of this form'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                'name'      => 'save-button',
                'id'        => self::BUTTON_TEXT_SAVE,
                VALUE => 'save-button',
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX  => $tabIndex,
            ]
        );
    }

    /**
     * @param string $icon name of glyphicon glyphicon-
     * @param string $pageTitle Title of view
     * @param string $subHeader subtitle of view
     */
    public static function header(
        $icon = 'user',
        $pageTitle = 'User',
        $subHeader = 'Users'
    ) {
        echo '<div class="row"><div class="col-sm-12">
                    <div class="border-bottom">
                        <h3 class="headerTitle ', self::COLOR_PRIMARY ,'"><strong>
                            <span class="glyphicon glyphicon-', $icon ,' ' ,
                                self::COLOR_PRIMARY , ' headerIcon">
                            </span> ', $pageTitle, '</strong>
                        </h3>
                    </div>
                    <p class="text-justify headerSubText">',$subHeader,'</p><br/>
            </div></div>';
    }

    /**
     * Show a panel in bootstrap 3.3.7
     * @param $panelTitle string header title of panel
     * @return string
     */
    public static function panel($panelTitle)
    {
        return '<div class="panel panel-default"><div class="panel-heading"><b>'.
        Yii::t('app', $panelTitle).'</b></div><div class="panel-body">';
    }

    /**
     * Close a Panel in view
     * @return string
     */
    public static function panelClose()
    {
        return '</div></div>';
    }

    /**
     * Show page header and navigation buttons of the index page.
     * @param string $icon
     * @param string $pageTitle
     * @param string $subHeader
     * @param string $table (name of database table)
     * @param string $showButtons 111 means (in correlative order)
     *               1:Show button New
     *               1: Show button Refresh
     *               1: Show button Delete
     * @param bool $showPageSize
     */
    public static function headerAdmin(
        $icon = 'user',
        $pageTitle = 'User',
        $subHeader = 'Users',
        $table = 'user',
        $showButtons = '111',
        $showPageSize = false
    ) {
        $nro_rows = Common::getNroRows($table);

        echo '<div class=\'row \'>
                    <div class=\'col-sm-6  \'>',
                        '<h3 class="headerTitle '.self::COLOR_PRIMARY.'"><strong>
                            <span class=\'glyphicon glyphicon-', $icon, self::COLOR_PRIMARY, ' headerIcon \'>',
                            '</span>',
                            $pageTitle,
                        self::HTML_SPACE,
                        '</strong><span class=\'nroRowsbadge \' title=\'',
                            Yii::t(
                                'app',
                                'Registers entered (It is updated when the form is loaded)'
                            ), '\' ', self::HTML_DATA_TOGGLE, '=', self::HTML_TOOLTIP, ' ',
                            self::HTML_DATA_PLACEMENT, '=',self::HTML_DATA_PLACEMENT_VALUE, '>', $nro_rows,
                        '</span></h3>',
                    '</div>
                    <div class=\'col-sm-6  text-right\'>';

        if ($showButtons) {
            UiComponent::buttonsAdmin($showButtons);
        }

        if ($showPageSize) {
            $page_size = UiComponent::pageSize();
            echo UiComponent::pageSizeDropDownList($page_size);
        }
        echo '      </div>
              </div>
              <p class=\'text-justify textSubHeader\'>',$subHeader,'</p>';
    }


    /**
     * @return array|mixed
     */
    public static function pageSize()
    {

        $session = Yii::$app->session;
        $page_size = Yii::$app->request->get(self::STR_PER_PAGE);

        if (isset($page_size)) {
            $page_size = Yii::$app->request->get(self::STR_PER_PAGE);
        } else {
            $page_size = Yii::$app->request->post(self::STR_PER_PAGE);
            if (isset($page_size)) {
                $page_size = Yii::$app->request->post(self::STR_PER_PAGE);
            } else {
                if (isset($session[self::STR_PAGESIZE])) {
                    $page_size  = $session[self::STR_PAGESIZE];
                } else {
                    $page_size=Yii::$app->params['pageSizeDefault'];
                    $session->set(self::STR_PAGESIZE, $page_size);
                }
            }
        }

        $session->set(self::STR_PAGESIZE, $page_size);
        return $page_size;
    }

    /**
     * @param $page_size
     * @return string
     */
    public static function pageSizeDropDownList($page_size)
    {
        $title= Yii::t('app', 'Number of rows to display per page');
        return Html::dropDownList(
            self::STR_PER_PAGE,
            $page_size,
            array(5=> 5, 10=> 10, 15 => 15, 25=> 25, 40=>40, 65=>65, 105=>105, 170=>170, 275=>275, 445=>445),
            array(
                'id' => self::STR_PER_PAGE,
                'onChange' => 'window.location.reload()',
                'title' => $title,
                'class' => 'btn btn-default dropdown-toggle dropdown-toggle-split',
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                )
        );
    }

    /**
     * @param $message string
     * @param $error array of string error message
     * @return void
     */
    public static function warning($message, $error)
    {
        $message = $message.'\n'. print_r($error, true);
        Yii::warning($message, __METHOD__);
        Yii::$app->session->setFlash('error', $message);
    }

    /**
     * @return array
     */
    public static function yesOrNoArray()
    {
        return [1=>Yii::t('app', 'Yes'), 0=>'No'];
    }

    /**
     * Show Yes or No given a boolean value
     *
     * @param $boolean
     * @return string yes or no
     */
    public static function yesOrNo($boolean)
    {
        return ($boolean==1)? Yii::t('app', 'Yes'):'No';
    }

    /**
     * @param $boolean
     * @return string
     */
    public static function yesOrNoGlyphicon($boolean)
    {
        return ($boolean)? 'glyphicon glyphicon-ok-circle': 'glyphicon glyphicon-remove-circle';
    }
}

<?php
/**
 * Ui
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

class UiComponent extends Component
{


    const COLOR_PRIMARY                 = ' text-primary '; // add one space blank before and after the color

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

    public  static function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);
        return '<span class="badge badge-'.$badge.'">'. $status . '</span>';
    }

    public  static function button($caption, $css, $buttonToolTip, $aAction = [])
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
     * @param $buttonHeader boolean Show header in view ? (true/false value)
     * @return void
     */

    public  static function buttonsAdmin($showButtons = '111', $buttonHeader = true)
    {
        $aShowButtons = str_split($showButtons, 1);

        $buttonCreate= '';
        if ($aShowButtons[0] && Common::getProfilePermission(ACTION_CREATE)) {
            $buttonCreate = UiComponent::button(
                self::BUTTON_ICON_CREATE. Yii::t('app', self::BUTTON_TEXT_CREATE),
                self::CSS_BTN_PRIMARY,
                Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                [ACTION_CREATE]
            );
        }

        $buttonDelete = '';
        if ($aShowButtons[2] && Common::getProfilePermission(ACTION_DELETE)) {
            $buttonDelete= UiComponent::buttonDelete(
                [ACTION_REMOVE],
                self::CSS_BTN_DEFAULT
            );
        }

        $buttonRefresh = '';
        if ($aShowButtons[1]) {
            $buttonRefresh = UiComponent::buttonRefresh();
        }

        if ($buttonHeader) {
            echo '<br/>',
            $buttonCreate,
            self::HTML_SPACE,
            $buttonRefresh,
            self::HTML_SPACE,
            $buttonDelete;
        } else {
            echo '<br/><br/><br/>',
            $buttonDelete,
            self::HTML_SPACE,
            $buttonRefresh,
            self::HTML_SPACE,
            $buttonCreate;
        }
    }

    /**
     * Show actions with buttons
     *
     * @param $model
     */

    public static function buttonsViewBottom(&$model)
    {
        $primaryKey = $model->getId();
        $buttonCreate = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $buttonCreate = UiComponent::button(
                self::BUTTON_ICON_CREATE. Yii::t('app', self::BUTTON_TEXT_CREATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', self::BUTTON_TEXT_TOOLTIP),
                [ACTION_CREATE]
            );
        }

        $buttonDelete = '';
        if (Common::getProfilePermission(ACTION_DELETE)) {
            $buttonDelete= UiComponent::buttonDelete(
                [ACTION_DELETE, 'id'=>$primaryKey],
                self::CSS_BTN_DANGER
            );
        }

        $buttonUpdate= '';
        if (Common::getProfilePermission(ACTION_UPDATE)) {
            $buttonUpdate = UiComponent::button(
                self::BUTTON_ICON_UPDATE . Yii::t('app', self::BUTTON_TEXT_UPDATE),
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Update the current record'),
                [ACTION_UPDATE, 'id'=>$primaryKey]
            );
        }

        echo $buttonCreate,
            self::HTML_SPACE,
            $buttonUpdate,
            self::HTML_SPACE,
            $buttonDelete,
            self::HTML_SPACE,
            UiComponent::button(
                self::BUTTON_ICON_BACK_INDEX . Yii::t('app', self::BUTTON_TEXT_BACK_INDEX),
                self::CSS_BTN_PRIMARY,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
    }

    public  static function buttonsCreate($tabIndex, $showBackToIndex = true)
    {
        $buttonSave = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $buttonSave = UiComponent::buttonSave($tabIndex);
        }

        echo $buttonSave,
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

    public  static function buttonDelete($action, $css)
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
     *
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
    public  static function panel($panelTitle)
    {
        return '<div class="panel panel-default"><div class="panel-heading"><b>'.
        Yii::t('app', $panelTitle).'</b></div><div class="panel-body">';
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
    public  static function headerAdmin(
        $icon = 'user',
        $pageTitle = 'User',
        $subHeader = 'Users',
        $table = 'user',
        $showButtons = '111',
        $showPageSize = false
    ) {
        $nroRows = Common::getNroRows($table);

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
                            self::HTML_DATA_PLACEMENT, '=',self::HTML_DATA_PLACEMENT_VALUE, '>', $nroRows,
                        '</span></h3>',
                    '</div>
                    <div class=\'col-sm-6  text-right\'>';

        if ($showButtons) {
            UiComponent::buttonsAdmin($showButtons);
        }

        if ($showPageSize) {
            $pageSize = UiComponent::pageSize();
            echo UiComponent::pageSizeDropDownList($pageSize);
        }
        echo '      </div>
              </div>
              <p class=\'text-justify textSubHeader\'>',$subHeader,'</p>';
    }


    public static function pageSize()
    {

        $session = Yii::$app->session;
        $pageSize = Yii::$app->request->get(self::STR_PER_PAGE);

        if (isset($pageSize)) {
            $pageSize = Yii::$app->request->get(self::STR_PER_PAGE);
        } else {
            $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            if (isset($pageSize)) {
                $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            } else {
                if (isset($session[self::STR_PAGESIZE])) {
                    $pageSize  = $session[self::STR_PAGESIZE];
                } else {
                    $pageSize=Yii::$app->params['pageSizeDefault'];
                    $session->set(self::STR_PAGESIZE, $pageSize);
                }
            }
        }

        $session->set(self::STR_PAGESIZE, $pageSize);
        return $pageSize;
    }

    public static function pageSizeDropDownList($pageSize)
    {
        $msg = Yii::t('app', 'Number of rows to display per page');
        return Html::dropDownList(
            self::STR_PER_PAGE,
            $pageSize,
            array(5=> 5, 10=> 10, 15 => 15, 25=> 25, 40=>40, 65=>65, 105=>105, 170=>170, 275=>275, 445=>445),
            array(
                'id' => self::STR_PER_PAGE,
                'onChange' => 'window.location.reload()',
                'title' => $msg,
                'class' => 'btn btn-default dropdown-toggle dropdown-toggle-split',
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                )
        );
    }

    /**
     * Get information for dropdown list
     * @param $model model class defined in app\models to get information
     * @param $parentModelId String column related model
     * @param $valueId integer id to search in model
     * @param $key integer column to get column code
     * @param $value string column to get column description
     * @param $orderBy String Order by column
     * @return string String
     */
    public  static function relatedDropdownList($model, $parentModelId, $valueId, $key, $value, $orderBy)
    {
        $rows = $model::find()->where([$parentModelId => $valueId])->orderBy([$orderBy => SORT_ASC])->all();

        $dropdown = Yii::t('app', 'Please select one option');
        $dropdown  = self::HTML_OPTION . $dropdown . self::HTML_OPTION_CLOSE;

        if (count($rows)>0) {
            foreach ($rows as $row) {
                $dropdown  .= '<option value='.$row->$key.'>'.$row->$value . self::HTML_OPTION_CLOSE;
            }
        } else {
            $dropdown  .= self::HTML_OPTION . Yii::t('app', 'No results found').self::HTML_OPTION_CLOSE;
        }

        return $dropdown ;
    }

    /**
     * @param $msg Message to show in view
     * @param $error String error message
     * @return void
     */
    public  static function warning($msg, $error)
    {

        $msg = $msg .'\n'. print_r($error, true);
        Yii::warning($msg, __METHOD__);
        Yii::$app->session->setFlash('error', $msg);
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

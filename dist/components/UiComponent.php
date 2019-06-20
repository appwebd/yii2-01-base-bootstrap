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

use app\controllers\BaseController;
use app\models\queries\Common;
use app\models\Status;
use Yii;
use yii\base\Component;
use yii\helpers\Html;

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


    const COLOR_PRIMARY = ' text-primary '; // add one space blank before and after the color
    const HTML_TOOLTIP = 'tooltip';
    const HTML_DATA_TOGGLE = 'data-toggle';
    const HTML_DATA_PLACEMENT = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE = 'top';
    const HTML_SPACE = '&nbsp;';
    const STR_PER_PAGE = 'per-page';

    /**
     * @param $statusId
     * @param $status
     * @return string
     */
    public static function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);
        return '<span class="badge badge-' . $badge . '">' . $status . '</span>';
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
    )
    {
        echo '<div class="row"><div class="col-sm-12">
                    <div class="border-bottom">
                        <h3 class="headerTitle ', self::COLOR_PRIMARY, '"><strong>
                            <span class="glyphicon glyphicon-', $icon, ' ',
        self::COLOR_PRIMARY, ' headerIcon">
                            </span> ', $pageTitle, '</strong>
                        </h3>
                    </div>
                    <p class="text-justify headerSubText">', $subHeader, '</p><br/>
            </div></div>';
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
        $showButtons = ' ',
        $showPageSize = false
    )
    {
        $nroRows = Common::getNroRows($table);

        echo '<div class=\'row \'>
                    <div class=\'col-sm-6  \'>',
            '<h3 class="headerTitle ' . self::COLOR_PRIMARY . '"><strong>
                            <span class=\'glyphicon glyphicon-', $icon, self::COLOR_PRIMARY, ' headerIcon \'>',
        '</span> &nbsp;',
        $pageTitle,
        self::HTML_SPACE,
        '</strong><span class=\'nroRowsbadge \' title=\'',
        Yii::t(
            'app',
            'Registers entered (It is updated when the form is loaded)'
        ), '\' ', self::HTML_DATA_TOGGLE, '=', self::HTML_TOOLTIP, ' ',
        self::HTML_DATA_PLACEMENT, '=', self::HTML_DATA_PLACEMENT_VALUE, '>', $nroRows,
        '</span></h3>',
        '</div>
                    <div class=\'col-sm-6  text-right\'>';

        if (isset($showButtons{3})) {
            echo $showButtons;
        }

        if ($showPageSize) {
            $pageSize = BaseController::pageSize();
            echo '<br>',UiComponent::pageSizeDropDownList($pageSize);
        }
        echo '      </div>
              </div>
              <p class=\'text-justify textSubHeader\'>', $subHeader, '</p>';
    }




    /**
     * @param $pageSize
     * @return string
     */
    public static function pageSizeDropDownList($pageSize)
    {
        $title = Yii::t('app', 'Number of rows to display per page');
        return Html::dropDownList(
            self::STR_PER_PAGE,
            $pageSize,
            array(5 => 5, 10 => 10, 15 => 15, 25 => 25, 40 => 40, 65 => 65, 105 => 105, 170 => 170, 275 => 275, 445 => 445),
            array(
                'id' => self::STR_PER_PAGE,
                'onChange' => 'window.location.reload()',
                TITLE => $title,
                'class' => 'btn btn-default dropdown-toggle dropdown-toggle-split',
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
            )
        );
    }

    /**
     * Show a panel in bootstrap 3.3.7
     * @param $panelTitle string header title of panel
     * @return string
     */
    public static function panel($panelTitle)
    {
        return '<div class="panel panel-default"><div class="panel-heading"><b>' .
            Yii::t('app', $panelTitle) . '</b></div><div class="panel-body">';
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
     * @return array
     */
    public static function yesOrNoArray()
    {
        return [1 => Yii::t('app', 'Yes'), 0 => 'No'];
    }

    /**
     * Show Yes or No given a boolean value
     *
     * @param $boolean
     * @return string yes or no
     */
    public static function yesOrNo($boolean)
    {
        return ($boolean == 1) ? Yii::t('app', 'Yes') : 'No';
    }

    /**
     * @param $boolean
     * @return string
     */
    public static function yesOrNoGlyphicon($boolean)
    {
        return ($boolean) ? 'glyphicon glyphicon-ok-circle' : 'glyphicon glyphicon-remove-circle';
    }
}

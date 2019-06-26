<?php
/**
 * Class UiComponent
 *
 * @package   Ui
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_version>
 * @link      https://appwebd.github.io
 * @date      6/28/18 2:33 PM
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
 * @package   Ui
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_version>
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:01 AM
 */
class UiComponent extends Component
{
    // add one space blank before and after the color (next line)
    const COLOR_PRIMARY = ' text-primary ';
    const HTML_TOOLTIP = 'tooltip';
    const HTML_DATA_TOGGLE = 'data-toggle';
    const HTML_DATA_PLACEMENT = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE = 'top';
    const HTML_SPACE = '&nbsp;';
    const STR_PER_PAGE = 'per-page';

    /**
     * @param  $statusId
     * @param  $status
     * @return string
     */
    public static function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);
        return '<span class="badge badge-' . $badge . '">' . $status . '</span>';
    }


    /**
     * Show a header in web page
     *
     * @param string $icon      name of glyphicon glyphicon-
     * @param string $pageTitle Title of view
     * @param string $subHeader subtitle of view
     *
     * @return void
     */
    public  function header(
        $icon = 'user',
        $pageTitle = 'User',
        $subHeader = 'Users'
    ) {
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
     *
     * @param string $icon         Icon symbol
     * @param string $pageTitle    Title of page
     * @param string $subHeader    Subheader of page
     * @param string $table        (name of database table)
     * @param string $showButtons  111 means (in correlative order)
     *                             1:Show button New 1: Show button
     *                             Refresh 1: Show button Delete
     * @param bool   $showPageSize Show page size
     *
     * @return void
     */
    public function headerAdmin(
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
            '<h3 class="headerTitle ' . self::COLOR_PRIMARY . '"><strong>
                            <span class=\'glyphicon glyphicon-',
        $icon, self::COLOR_PRIMARY, ' headerIcon \'>',
        '</span> &nbsp;',
        $pageTitle,
        self::HTML_SPACE,
        '</strong><span class=\'nroRowsbadge \' title=\'',
        Yii::t(
            'app',
            'Registers entered (It is updated when the form is loaded)'
        ), '\' ', self::HTML_DATA_TOGGLE, '=', self::HTML_TOOLTIP, ' ',
        self::HTML_DATA_PLACEMENT, '=',
        self::HTML_DATA_PLACEMENT_VALUE, '>', $nroRows,
        '</span></h3>',
        '</div>
                    <div class=\'col-sm-6  text-right\'>';

        if (isset($showButtons)) {
            $buttonsAdmin = new UiButtons();
            $buttonsAdmin->buttonsAdmin($showButtons);
        }

        if ($showPageSize) {
            $pageSize = BaseController::pageSize();
            echo '<br>', UiComponent::pageSizeDropDownList($pageSize);
        }
        echo '      </div>
              </div>
              <p class=\'text-justify textSubHeader\'>', $subHeader, '</p>';
    }


    /**
     * Page Size Dropdown
     *
     * @param int $pageSize Show a dropdown in view
     *
     * @return string
     */
    public static function pageSizeDropDownList($pageSize)
    {
        $title = Yii::t('app', 'Number of rows to display per page');
        return Html::dropDownList(
            self::STR_PER_PAGE,
            $pageSize,
            array(5 => 5, 10 => 10, 15 => 15, 25 => 25, 40 => 40, 65 => 65,
                105 => 105, 170 => 170, 275 => 275, 445 => 445),
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
     *
     * @param string $panelTitle header title of panel
     *
     * @return string
     */
    public function panel($panelTitle)
    {
        return '<div class="panel panel-default"><div class="panel-heading"><b>' .
            Yii::t('app', $panelTitle) . '</b></div><div class="panel-body">';
    }

    /**
     * Close a Panel in view
     *
     * @return string
     */
    public function panelClose()
    {
        return '</div></div>';
    }

    /**
     * Return array yes/no
     *
     * @return array
     */
    public static function yesOrNoArray()
    {
        return [1 => Yii::t('app', 'Yes'), 0 => 'No'];
    }

    /**
     * Show Yes or No given a boolean value
     *
     * @param bool $boolean Show yes or not
     *
     * @return string yes or no
     */
    public static function yesOrNo($boolean)
    {
        return ($boolean == 1) ? Yii::t('app', 'Yes') : 'No';
    }

    /**
     * To show glyphicon
     *
     * @param bool $boolean show glyphicon attribute
     *
     * @return string
     */
    public static function yesOrNoGlyphicon($boolean)
    {
        return ($boolean) ? 'glyphicon glyphicon-ok-circle' :
            'glyphicon glyphicon-remove-circle';
    }
}

<?php


namespace app\components;

use app\models\queries\Bitacora;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\base\Component;

/**
 * Class TaskDelete
 *
 * @category  Components
 * @package   Task
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_version>
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:01 AM
 */
class DeleteRecord extends Component
{

    /**
     * Verify permissions to delete records
     *
     * @param string $action valid if this request is Post and get profile permission
     *
     * @return boolean
     */
    public function isOkPermission($action)
    {
        if (!Yii::$app->request->isPost) {
            $event = Yii::t(
                'app',
                'Page not valid Please do not repeat this requirement.
                All site traffic is being monitored'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'isOkPermission',
                MSG_SECURITY_ISSUE
            );

            return false;
        }

        if (!Common::getProfilePermission($action)) {
            $event = Yii::t(
                'app',
                'Your account don\'t have priviledges for this action,
        please do not repeat this requirement. All site traffic is being monitored'
            );

            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'isOkPermission',
                MSG_SECURITY_ISSUE
            );
            return false;
        }

        return true;
    }

    /**
     * Verify if the variable $result has information
     * (used for delete records of gridview)
     *
     * @param string $result
     *
     * @return bool
     */
    public function isOkSelection($result)
    {
        if (!isset($result)) {
            $event = Yii::t(
                'app',
                'called to remove items,
    but has not send selection of records to remove: Possible Security issue event?'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'isOkSeleccionItems',
                MSG_SECURITY_ISSUE
            );
            return false;
        }
        return true;
    }

    /**
     * To show information about status delete record
     *
     * @param integer $status false=0/true=1/2 of transaction delete record in table
     *
     * @return void
     */
    public function report($status)
    {
        switch ($status) {
            case 0:
                $msg_text = 'There was an error removing the record';
                $msg_status = ERROR;
                break;
            case 1:
                $msg_text = 'Record has been deleted';
                $msg_status = SUCCESS;
                break;
            default:
                $msg_text = 'Record could not be deleted because it is being used in the system';
                $msg_status = ERROR;
                break;
        }

        $msg_text = Yii::t('app', $msg_text);
        Yii::$app->session->setFlash($msg_status, $msg_text);
    }

    public function remove($model, $fkCheck)
    {
        $ok_transaction = 0; // 0: OK was deleted
        if ($model == null) {
            $ok_transaction = 3;  // 3: Not found record in the system
        }
        if ($fkCheck > 0) {
            $ok_transaction = 2; // Record used in the system
        }

        if ($ok_transaction == 0) {
            try {
                Common::transaction($model, ACTION_DELETE);
                $ok_transaction = 0;
            } catch (Exception $exception) {
                $ok_transaction = 1;
                $bitacora = new Bitacora();
                $bitacora->register(
                    $exception,
                    'app\composer\DeleteRecord::remove',
                    MSG_ERROR
                );
            }
        }
        return $ok_transaction;
    }

    /**
     * Resume of operation
     *
     * @param array $status String with summary of all the records deleted
     */
    public function summaryDisplay($status)
    {
        $ids = $status[0];
        if (isset($ids{2})) {
            $msg = 'Records selected: \'{ids}\' has been deleted.';
            $this->summaryItem($msg, $ids, MSG_SUCCESS);
        }
        $ids = $status[1];
        if (isset($ids{2})) {
            $msg = 'Selected records: \'{ids}\' a problem occurred removing the record';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }
        $ids = $status[2];
        if (isset($ids{2})) {
            $msg = 'Selected records: \'{ids}\' have not been deleted, they are being used in the system';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }
        $ids = $status[3];
        if (isset($ids{2})) {
            $msg = 'Selected records: \'{ids}\' was not found in the database';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }
    }

    public function summaryItem($msg, $ids, $statusId)
    {
        $event = Yii::t('app', $msg, ['ids' => $ids]);
        $bitacora = new Bitacora();
        $bitacora->registerAndFlash($event, 'summaryDisplayItem', $statusId);
    }
}

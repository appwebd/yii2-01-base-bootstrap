<?php

/**
 * PHP Version 7.3.0
 *
 * @category Widget
 * @package  Widget
 * @author   Kartik Visweswaran <kartikv2@gmail.com>
 * @author   Alexander Makarov <sam@rmcreative.ru>
 * @license  https://www.yiiframework.com/license.html MIT?
 * @link     https://www.yiiframework.com
 */

namespace app\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash.
 * All flash messages are displayed
 * in the sequence they were assigned using setFlash.
 * You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @category Widget
 * @package  Widget
 * @author   Kartik Visweswaran <kartikv2@gmail.com>
 * @author   Alexander Makarov <sam@rmcreative.ru>
 * @license  https://www.yiiframework.com/license.html MIT?
 * @link     https://www.yiiframework.com
 */
class Alert extends \yii\bootstrap\Widget
{
    const STR_CLASS = 'class';

    /**
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     *
     * @var array the alert types configuration for the flash messages.
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     *
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options[STR_CLASS]) ?
            ' ' . $this->options[STR_CLASS]
            : '';

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                echo \yii\bootstrap\Alert::widget(
                    [
                        'body' => $message,
                        'closeButton' => $this->closeButton,
                        'options' => array_merge(
                            $this->options,
                            [
                                'id' => $this->getId() . '-' . $type . '-' . $i,
                                self::STR_CLASS => $this->alertTypes[$type]
                                    . $appendClass,
                            ]
                        ),
                    ]
                );
            }

            $session->removeFlash($type);
        }
    }
}

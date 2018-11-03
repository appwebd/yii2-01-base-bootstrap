<?php
/**
 * Class LanguageSelector
 *
 * @package     LanguageSelector
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-10-19 14:15:15 pm
 * @version     1.0
 */

namespace app\components;

use yii\base\BootstrapInterface;

/**
 * Class LanguageSelector
 * @package app\components
 */
class LanguageSelector implements BootstrapInterface
{
    /**
     * @var array
     */
    public $suppLanguages = ['es', 'en'];

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {

        $preflang = $app->request->getPreferredLanguage($this->suppLanguages);

        $app->language = $preflang;
    }
}

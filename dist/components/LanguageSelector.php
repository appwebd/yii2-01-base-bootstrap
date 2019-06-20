<?php
/**
 * Class LanguageSelector
 *
 * @category  Components
 * @package   LanguageSelector
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas
 * @license   Private license
 * @link      https://appwebd.github.io
 * @date      2018-10-19 14:15:15 pm
 * @version   1.0
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
    public $supportedLanguages = ['es', 'en'];

    /**
     * @param object $app \yii\base\Application
     */
    public function bootstrap($app)
    {

        $preflang = $app->request->getPreferredLanguage($this->supportedLanguages);
        $app->language = $preflang;
    }
}

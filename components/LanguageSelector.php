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

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = ['es_ES', 'en_EN'];
    public function bootstrap($app)
    {

        $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);

        $app->language = $preferredLanguage;
    }
}
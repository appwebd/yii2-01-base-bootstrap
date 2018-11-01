<?php
/**
 * Event handler for missing translations
 *
 * @package     TranslationEventHandler
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        7/1/18 11:37 AM
 * @version     1.0
 */


namespace app\components;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = "@MISSING: FOR LANGUAGE {$event->language}  {$event->category}, {$event->message} @";
    }
}

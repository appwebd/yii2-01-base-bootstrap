<?php
namespace app\helpers;

use Yii;
use Swift_SwiftException;

/**
 * A helper class for sending mails
 */
class Mail
{

    /**
     * Send an user email
     *
     * The view is rendered from `@app/mails/user` with the
     * `@app/mails/layouts/user` layout applied.
     *
     * The sender can be specified in `$params['from']` and defaults to the
     * `mail.from` application parameter.
     *
     * @param $user primary key of table user
     * @param $subject string subject of email
     * @param string $view name of the view file to render from `@app/mail/user`
     * @return bool whether the mail was sent successfully
     */
    public static function sendEmail($user, $subject, $view)
    {

        try {
            $from   = Yii::$app->params['adminEmail'];
            $sendOK = Yii::$app
                ->mailer
                ->compose(
                    ['html' => $view.'-html', 'text' => $view.'-text'],
                    [
                        'model' => $user
                    ]
                )
                ->setFrom($from)
                ->setTo($user->email)
                ->setSubject($subject)
                ->send();

            if ($sendOK) {
                $msg= Yii::t('app', 'An email has been sent to your email account');
                Yii::info($msg, __METHOD__);
                return true;
            } else {
                $msg= Yii::t('app', 'The sending of emails to your email account has failed:'. $user->email);
                Yii::warning($msg, __METHOD__);
                return false;
            }
        } catch (Swift_SwiftException $e) {
            $type = get_class($e);
            $message = $e->getMessage();
            $trace = $e->getTraceAsString();
            Yii::warning("Swift exception $type:\n$message\n\n$trace", __METHOD__);
        }

        return false;
    }
}

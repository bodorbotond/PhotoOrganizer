<?php

/*
 static class for send email to users who forgot her/his passwords

 email is sended by call sendEmail() method
							 - attributes:
								 - $eMail (string): target e-mail adress
								 - $messageParams (array<string>):  $messageParams['userName'] 		  => username,
							 										$messageParams['verificationKey'] => verification key
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class UserContactSendEmail
{
	
	public static function sendEMail($name, $eMail, $subject, $bodyText)
	{	
		return Yii::$app->mailer
						->compose()
						->setTo(Yii::$app->params['adminEmail'])
						->setFrom($eMail)
						->setSubject($subject)
						->setTextBody($bodyText)
						->send();
	}
	
}
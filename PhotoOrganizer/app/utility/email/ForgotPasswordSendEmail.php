<?php

/*
 static class for send email to users who forgot her/his passwords

 email is sended by call sendEmail() method
 		- attributes:
			- $eMail (string): target e-mail adress
			- $messageParams (array<string>): $messageParams['userName'] 		=> username,
							  				  $messageParams['verificationKey'] => verification key 
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;

class ForgotPasswordSendEmail
{
	
	public static function sendEMail($eMail, $messageParams)
	{
		$message = self::buildMessage($messageParams);
		
		return Yii::$app->mailer
				->compose('layouts\html', ['content' => $message])
				->setTo($eMail)
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject('Registration to Photo Organizer Application')
				->setHtmlBody($message)
				->send();
	}
	
	private static function buildMessage($messageParams)
	{
		return '
				<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>
						It looks like forgot your password on <b>Photo Organizer</b> website. <br>
						Please confirm your change password intention.<br>
						Your verification key: ' .
							$messageParams['verificationKey'] .
							'</p>' .
							//Html::a('Verification', Url::home('http') . 'user/forgotPassword/verificationKey') .
							'<p>
						<b>Photo Organizer Team</b>
					</p>
				</div>
				';
	}
}
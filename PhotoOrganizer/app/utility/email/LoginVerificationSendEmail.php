<?php

/*
this class provide send e-mail functionality to users whom two step verification is active

email is sended by call sendEmail() method
 		- attributes:
			- $eMail (string): target e-mail adress
			- $messageParams (array<string>): $messageParams['userName'] 		=> username,
							  				  $messageParams['verificationKey'] => verification key 
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;


class LoginVerificationSendEmail
{

	public static function sendEmail($eMail, $messageParams)		// send email with login verification key
	{
		$message = self::buildMessage($messageParams);
		 
		return Yii::$app->mailer
				->compose('layouts\html', ['content' => $message])
				->setTo($eMail)
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject('Login to Photo Organizer Website')
				->setHtmlBody($message)
				->send();
	}
	
	
	private static function buildMessage($messageParams)			// build email HTML content
	{
		return '<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>
						You try to login for <b>Photo Organizer</b> website. <br>
						Please confirm your login intention. <br>
						Your activation key: <br>' .
							$messageParams['verificationKey'] .
							'</p>' .
							Html::a('Confirm login', Url::home('http') . 'user/login/loginVerification') .
							'<p>
						<b>Photo Organizer Team</b>
					</p>
				</div>';
	}
}
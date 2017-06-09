<?php

/*
 this class provide send e-mail functionality to users whom two step verification is active

 email is sended by call sendEmail() method
 				- attributes:
 					- $eMail (string): target e-mail adress
 					- $messageParams (array<string>): $messageParams['userName'] 		=> username,
 													  $messageParams['eMail']			=> e-mail,
 													  $messageParams['verificationKey'] => verification key
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;


class SignUpVerificationSendEmail
{
	public static function sendEmail($eMail, $messageParams)		// send email with signup verification key to user
	{
		$message = self::buildMessage($messageParams);
			
		return Yii::$app->mailer
		->compose('layouts\html', ['content' => $message])
		->setTo($eMail)
		->setFrom(Yii::$app->params['adminEmail'])
		->setSubject('Registration to Photo Organizer Website')
		->setHtmlBody($message)
		->send();
	}
	
	
	private static function buildMessage($messageParams)			// build email HTML content
	{
		return '<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>
						<p>
						Thanks for signing up for <b>Photo Organizer</b> with ' . $messageParams['eMail'] . ' email address! <br>
						Please confirm your account. <br>
						Your activation key: <br>' .
						$messageParams['verificationKey'] .
					'</p>' .
					Html::a('Confirm account', Url::home('http') . 'user/signUp/signUpVerification') .
					'<p>
						<b>Photo Organizer Team</b>
					</p>
				</div>';
	}
}
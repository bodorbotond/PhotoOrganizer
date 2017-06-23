<?php

/*
 static class for send email to users who forgot her/his passwords

 email is sended by call sendEmail() method
 		- attributes:
			- $eMail (string): target e-mail adress
			- $subject (string): e-mail's subject
			- $function (string): e-mail's purpose
			- $messageParams (array<string>): $messageParams['userName'] 			=> target username,
											  $messageParams['administratorName'] 	=> group administrator username,
											  $messageParams['groupName'] 			=> group name,
							  				  $messageParams['groupVisibility'] 	=> group visibility (private or public)
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class GroupMemberSendEmail
{
	
	public static function sendEMail($eMail, $subject, $function, $messageParams)
	{
		if ($function === 'addUser')
		{
			$message = self::buildMessageToAddedUser($messageParams);
		}
		elseif ($function === 'removeUser')
		{
			
		}
		elseif ($function === 'joinUser')
		{
			$message = self::buildMessageToAdministratorAtJoin($messageParams);
		}
		elseif ($function === 'leaveGroup')
		{
			
		}
		
		
		return Yii::$app->mailer
				->compose('layouts\html', ['content' => $message])
				->setTo($eMail)
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject($subject)
				->setHtmlBody($message)
				->send();
	}
	
	
	private static function buildMessageToAddedUser($messageParams)
	{
		return '<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>'
						. $messageParams['administratorName']. ' added you to '
						. $messageParams['groupName'] . ' '
						. $messageParams['groupVisibility'] . ' group. <br>
						Here you can share your photos with other group members or you can view other member\'s photos.'						
					. '</p><p><b>Photo Organizer Team</b></p>
				</div>';
	}
	
	
	private static function buildMessageToAdministratorAtJoin($messageParams)
	{
		return '<h1>Hi ' . $messageParams['administratorName'] .',</h1>
				<div>
					<p>'
						. $messageParams['userName'] . ' want to join your'
						. $messageParams['groupName'] . ' '
						. $messageParams['groupVisibility'] . ' group. <br>'
						. Html::a('Here', Url::home('http') . 'groups/index')
						. ' you can accept or deny' . $messageParams['userName'] . 'request.'
						. '</p><p><b>Photo Organizer Team</b></p>
				</div>';
	}
}
<?php

/*
 static class for send email to users about groups

 email is sended by call sendEmail() method
 		- attributes:
			- $eMail (string): target e-mail adress
			- $subject (string): e-mail's subject
			- $function (string): e-mail's purpose('addUser', 'removeUser', 'joinUser', 'leaveGroup')
			- $messageParams (array<string>): $messageParams['userName'] 			=> target username,
											  $messageParams['administratorName'] 	=> group administrator username,
											  $messageParams['groupName'] 			=> group name,
							  				  $messageParams['groupVisibility'] 	=> group visibility (private or public)
							  				  $messageParams['groupId'] 			=> group id
 */

namespace app\utility\email;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class GroupMemberSendEmail
{
	
	public static function sendEMail($eMail, $subject, $function, $messageParams)
	{	
		$message = self::buildMessage($function, $messageParams);
		
		return Yii::$app->mailer
				->compose('layouts\html', ['content' => $message])
				->setTo($eMail)
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject($subject)
				->setHtmlBody($message)
				->send();
	}
	
	private static function buildMessage($function, $messageParams)
	{
		if ($function === 'addUser')
		{
			$message = self::buildMessageToAddedUser($messageParams);
		}
		elseif ($function === 'removeUser')
		{
			$message = self::buildMessageToRemoveUserFromGroup($messageParams);
		}
		elseif ($function === 'joinUser')
		{
			$message = self::buildMessageToAdministratorAtJoin($messageParams);
		}
		elseif ($function === 'leaveGroup')
		{
			$message = self::buildMessageToLeaveGroup($messageParams);
		}
		
		return $message;
	}
	
	
	private static function buildMessageToAddedUser($messageParams)
	{
		return '<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>'
						. $messageParams['administratorName']. ' added you to '
						. $messageParams['groupName'] . ' '
						. $messageParams['groupVisibility'] . ' group. <br>
						Here you can share your photos with other group members or you can view other member\'s photos. <br>'						
						. Html::a('View Group', Url::home('http') . 'groups/view/' . $messageParams['groupId'])
					. '</p>
					<p><b>Photo Organizer Team</b></p>
				</div>';
	}
	
	
	private static function buildMessageToRemoveUserFromGroup($messageParams)
	{
		return '<h1>Hi ' . $messageParams['userName'] .',</h1>
				<div>
					<p>'
						. $messageParams['administratorName'] . ' removed you from '
						. $messageParams['groupName'] . ' '
						. $messageParams['groupVisibility'] . ' group. <br>'
						. Html::a('Here', Url::home('http') . 'search/searchGroup')
						. ' you can search for other groups to join.
					</p>
					<p><b>Photo Organizer Team</b></p>
				</div>';
	}
	
	
	private static function buildMessageToAdministratorAtJoin($messageParams)
	{
		return '<h1>Hi ' . $messageParams['administratorName'] .',</h1>
				<div>
					<p>'
				. $messageParams['userName'] . ' want to join your '
						. $messageParams['groupName'] . ' '
								. $messageParams['groupVisibility'] . ' group. <br>'
										. Html::a('Here', Url::home('http') . 'groups/view/' . $messageParams['groupId'])
										. ' you can accept or deny ' . $messageParams['userName'] . ' request.'
												. '</p>
					<p><b>Photo Organizer Team</b></p>
				</div>';
	}
	
	
	private static function buildMessageToLeaveGroup($messageParams)
	{
		return '<h1>Hi ' . $messageParams['administratorName'] .',</h1>
				<div>
					<p>'
						. $messageParams['userName'] . ' leaved your '
						. $messageParams['groupName'] . ' '
						. $messageParams['groupVisibility'] . ' group. <br>'
						. Html::a('Here', Url::home('http') . 'search/searchUser')
						. ' you can search for other users to add your groups.
					</p>
					<p><b>Photo Organizer Team</b></p>
				</div>';
	}
}
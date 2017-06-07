<?php

// this class provide a record from Users table by username or email

namespace app\utility;

use yii\web\Session;
use app\models\Users;


class IdentifyUser
{
	public static function getUserFromSessionByUsernameOrEmail()
	{		

		$session = new Session();
		$session->open();
		
		if (isset($session['username']))						// if identified happened by username
		{
			return Users::findByUsername($session['username']);	// find user by username
		}
		elseif (isset($session['email']))						// if identified happened by email
		{
			return Users::findByEMail($session['email']);			// find user by email
		}
		else
		{
			return null;										// else wrong usernam or email
		}
	}
}
<?php

// this class's task is handle session

namespace app\utility;

use yii\web\Session;

class SessionManager
{
	
	public static function checkSessionArray($sessionArray)		// return true or false depending on exist variables from array in session
	{
		$session = new Session();
		$session->open();							// open session

		foreach ($sessionArray as $key => $value)
		{
			if (isset($session[$value]))			// if one array element does not exists in array return false		
			{
				return true;
			}
		}
		
		return false;								// else return true
	}
	
	
	public static function setSession($key, $value)		// set session variable by key
	{
		$session = new Session();
		$session->open();
		$session[$key] = $value;
		$session->close();
	}
	
	public static function getSession($key)		// return session variable by key
	{
		$session = new Session();
		$session->open();
		return $session[$key];
	}
	
	public static function deleteSessionArray($sessionArray)
	{
		$session = new Session();
		$session->open();							// open session
		
		foreach ($sessionArray as $key => $value)
		{
			unset($session[$value]);				// delete array elements from session
		}
		
		$session->close();
	}
}
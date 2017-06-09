<?php

namespace app\models\forgotPassword;

use Yii;
use yii\base\Model;
use app\utility\SessionManager;
use app\models\Users;

class UserIdentifyForm extends Model
{
	public $usernameOrEmail;

	public function rules()
	{
		return [
				// usernameOrEmail is required
				[['usernameOrEmail'], 'required'],
				// usernameOrEmail is validated by validateUsernameOrEmail()
				['usernameOrEmail', 'validateUsernameOrEmail'],
		];
	}

	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'usernameOrEmail' => 'Username Or E-mail',
		];
	}
	
	public function validateUsernameOrEmail($attribute, $params)
	{		
		if (!Users::findByUsername($this->usernameOrEmail))
		{
			if (!Users::findByEMail($this->usernameOrEmail))
			{
				$this->addError($attribute, 'Invalid username or e-mail!');
			}
			else
			{
				SessionManager::setSession('email', $this->usernameOrEmail);	// set email in session variable
			}
		}
		else
		{
			SessionManager::setSession('username', $this->usernameOrEmail);	// set username in session variable
		}		
	}
}
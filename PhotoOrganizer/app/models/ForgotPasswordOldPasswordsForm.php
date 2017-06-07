<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\utility\IdentifyUser;

class ForgotPasswordOldPasswordsForm extends Model
{
	public $oldPassword;

	public function rules()
	{
		return [
				// oldPassword is required
				[['oldPassword'], 'required'],
				// oldPassword is validated by validateOldPassword()
				['oldPassword', 'validateOldPassword'],
		];
	}

	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'oldPassword' => 'Old Password',
		];
	}
	
	public function validateOldPassword($attribute, $params)
	{
		
		if (strlen($this->oldPassword) < 8 || strlen($this->oldPassword) > 50)		// check password length
		{
			$this->addError($attribute, 'The length of password must be between 8 and 50 character!');
		}
		
		$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();	// get user by username or email
		
		if (crypt($this->oldPassword, 'salt') === $user->password)		// if entered old password is recently password
		{
			$this->addError($attribute, 'You use this password recently!');
		}
				
		if(!$this->oldPasswordFoundByUserId($user->user_id))			// check exists any old password which is equal to entered password
		{
			$this->addError($attribute, 'Wrong old password!');
		}
				
	}
	
	public function oldPasswordFoundByUserId($id)							// check exists any old password which is equal to entered password
	{																			// old passwords are queried by user id
		//echo '<br><br><br>';
		foreach (OldPasswords::findByUserId($id) as $key=>$value)
		{
			//echo $value['old_password'] .'&nbsp;&nbsp;' . crypt($this->oldPassword, 'salt') . '<br>';
			if (hash_equals(crypt($value['old_password'], 'salt'), $this->oldPassword))
			{
				return true;
			}
		}
		return false;
	}

}

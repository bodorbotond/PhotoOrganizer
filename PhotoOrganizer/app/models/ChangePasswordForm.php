<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
	
	public $oldPassword;
    public $newPassword;
    public $confirmedNewPassword;

    public function rules()
    {
        return [
            // oldPassword, newPassword, confirmedNewPassword are required
            [['oldPassword', 'newPassword', 'confirmedNewPassword'], 'required'],
        	// oldPassword is validated by validateoldPassword()
        	[['oldPassword'], 'validateoldPassword'],
        	// newPassword is validated by validatenewPassword()
        	[['newPassword'], 'validatenewPassword'],
        	// confirmedNewPassword is validated by validateconfirmedNewPassword()
        	[['confirmedNewPassword'], 'validateconfirmedNewPassword'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'oldPassword' 			=> 'Old Password',
    			'newPassword'			=> 'New Password',
    			'confirmedNewPassword'	=> 'Confirm New Password',
    	];
    }
    
    public function validateoldPassword($attribute, $params)
    {
    	$user = Users::findOne(Yii::$app->user->identity->id);
    	
    	if (!$user->validatePassword($this->oldPassword))
    	{
    		$this->addError($attribute, 'Wrong old password!');
    	}
    }
    
    public function validatenewPassword($attribute, $params)
    {
    	if (strlen($this->newPassword) < 8 || strlen($this->newPassword) > 50)
    	{
    		$this->addError($attribute, 'The length of password must be between 8 and 50 character!');
    	}
    	
    	// check the password contains at least one character and at least one number and at least one special character(all characters that's not a digit or a-Z)
    	if (!preg_match('/[a-zA-Z]+/', $this->newPassword) || !preg_match('/\d+/', $this->newPassword) || !preg_match('/[^a-zA-Z\d]+/', $this->newPassword))
    	{
    		$this->addError($attribute, 'The password must be contain at least one number, one special character and one letter!');
    	}
    	
    	// check oldPassword is equal the new newPassword
    	if ($this->oldPassword === $this->newPassword)
    	{
    		$this->addError($attribute, 'You use this password recently!');
    	}
    }
    
    public function validateconfirmedNewPassword($attribute, $params)
    {
    	// check newPassword and confirmedNewPassword is the same newPassword
    	if ($this->newPassword !== $this->confirmedNewPassword)
    	{
    		$this->addError($attribute, 'Password don\'t match!');
    	}
    }
    
    public function changePassword()
    {
    	$user = Users::findOne(Yii::$app->user->identity->id);
    	
    	if ($this->validate())
    	{
    		$user->password = crypt($this->newPassword, 'salt');
    		
    		if ($user->update())
    		{
    			return true;
    		}
    	}
    	return false;
    }
    
}

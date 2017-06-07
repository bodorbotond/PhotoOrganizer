<?php

namespace app\models\user;

use Yii;
use yii\base\Model;
use yii\web\Session;
use app\utility\IdentifyUser;
use app\models\Users;
use app\models\OldPasswords;

class ChangePasswordForm extends Model
{
	
    public $newPassword;
    public $confirmedNewPassword;

    public function rules()
    {
        return [
            // newPassword, confirmedNewPassword are required
            [['newPassword', 'confirmedNewPassword'], 'required'],
        	// newPassword is validated by validateNewPassword()
        	[['newPassword'], 'validateNewPassword'],
        	// confirmedNewPassword is validated by validateConfirmedNewPassword()
        	[['confirmedNewPassword'], 'validateConfirmedNewPassword'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'newPassword'			=> 'New Password',
    			'confirmedNewPassword'	=> 'Confirm New Password',
    	];
    }
    
    public function validateNewPassword($attribute, $params)
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
    	
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();    	
    	if (crypt($this->newPassword, 'salt') === $user->password)			// if entered new password is recently password
    	{
    		$this->addError($attribute, 'You use this password recently!');
    	}
    	
    	// if user has permission to change password (complete one password remembering option)
    	
    	$session = new Session();
    	$session->open();
    	
    	if (!isset($session['changePasswordPermission']) || !$session['changePasswordPermission'])
    	{
    		$this->addError('identifyFail', 'Try to identify yourself again!');
    	}
    	
    	$session->close();
    	
    }
    
    public function validateConfirmedNewPassword($attribute, $params)
    {
    	// check newPassword and confirmedNewPassword is the same newPassword
    	if ($this->newPassword !== $this->confirmedNewPassword)
    	{
    		$this->addError($attribute, 'Password don\'t match!');
    	}
    }
    
    public function changePassword()
    {
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();    	
    	$oldPassword = new OldPasswords();								// insert old password to database (this may help if the user forgot password)
    	
    	if ($this->validate())
    	{
    		$oldPassword->user_id = $user->user_id;
    		$oldPassword->old_password = crypt($user->password, 'salt');
    		
    		$user->password = crypt($this->newPassword, 'salt');
    		
    		if ($user->update() && $oldPassword->save())
    		{
    			return true;
    		}
    	}
    	return false;
    }
    
}

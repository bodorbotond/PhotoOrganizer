<?php

namespace app\models\account;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\EmailValidator;
use app\models\Users;

class RecoveryEmailForm extends Model
{
	
    public $recoveryEmail;

    public function rules()
    {
        return [
            // recovery emailis required
            [['recoveryEmail'], 'required'],
        	// recoveryEmail is validated by validateUserStatus()
        	[['recoveryEmail'], 'validateRecoveryEmail'],
        ];
    }

    public function validateRecoveryEmail($attribute, $params)
    {
    	$validator = new EmailValidator();
    	
    	if (!$validator->validate($this->recoveryEmail, $error))
    	{
    		$this->addError($attribute, /*'Email is not valid!'*/$error);
    	}
    	
    	if (Users::findByEMail($this->recoveryEmail))
    	{
    		$this->addError($attribute, 'With this email is already signed up an other user!');
    	}
    	
    	if (strlen($this->recoveryEmail) > 50)
    	{
    		$this->addError($attribute, 'The length of E-mail must be between 0 and 50 character!');
    	}
    }
    
    public function addOrModifyRecoveryEmail()
    {
    	$user = Users::findOne(Yii::$app->user->identity->id);				// get logged in user
    	
    	if ($this->validate())
    	{
    		$user->recovery_e_mail = $this->recoveryEmail;
    		
    		if ($user->update())
    		{
    			return true;
    		}
    	}
    	else
    	{
    		return false;
    	}
    }
    
    public function deleteRecoveryEmail()
    {
    	$user = Users::findOne(Yii::$app->user->identity->id);
    	$user->recovery_e_mail = null;
    	return $user->update();
    }
    
}

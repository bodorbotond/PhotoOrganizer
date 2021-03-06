<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;
use app\models\Users;

class LoginVerificationForm extends Model
{
	public $verificationKey;

	public function rules()
	{
		return [
				// verificationKey is required
				[['verificationKey'], 'required'],
				// verificationKey is validated by validateVerificationKey()
				['verificationKey', 'validateVerificationKey'],
		];
	}

	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'verificationKey' => 'Verification Key',
		];
	}
	
	public function validateVerificationKey($attribute, $params)
	{
		if (Yii::$app->getSession()->hasFlash('username'))				// check if username is in session
		{
			$username = Yii::$app->getSession()->getFlash('username');		// get username from session variable
			$user = Users::findOne(['user_name' => $username]);				// get user
			
			if ($this->verificationKey !== $user->verification_key)			// check if input verification key is equal with user's (getting by username from session variable) verification key
			{
				$this->addError($attribute, 'Wrong verification key!');
			}
		}
		else
		{
			$this->addError($attribute, 'Try to login again!');			// there is no username in session
		}
	}
	
	public function verification()
	{
		if ($this->validate())
		{
			return true;
		}
		return false;
	}

}
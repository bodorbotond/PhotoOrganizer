<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Security;
use app\models\Users;

class SignUpVerificationForm extends Model
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
		if(!Users::findByUserToken($this->verificationKey))
		{
			$this->addError($attribute, 'Wrong verification key!');
		}
		else
		{
			$user = Users::findByUserToken($this->verificationKey);
			if ($user->user_status == 'active')
			{
				$this->addError($attribute, 'This account was already confirmed!');
			}
		}
	}

	public function verification()
	{
		if ($this->validate())
		{
			$user = Users::findByUserToken($this->verificationKey);
			$user->user_status = 'active';
			return $user->update();
		}
		else 
		{
			return false;
		}
	}
}
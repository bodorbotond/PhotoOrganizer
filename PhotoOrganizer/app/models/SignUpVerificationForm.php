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
		if(!Users::findByVerificationKey($this->verificationKey))
		{
			$this->addError($attribute, 'Wrong verification key!');
		}
		else
		{
			$user = Users::findByVerificationKey($this->verificationKey);
			if ($user->account_status == 'active')
			{
				$this->addError($attribute, 'This account was already activated!');
			}
		}
	}

	public function verification()
	{
		if ($this->validate())
		{
			$user = Users::findByVerificationKey($this->verificationKey);
			$user->account_status = 'active';
			return $user->update();
		}
		else 
		{
			return false;
		}
	}
}
<?php

namespace app\models\forgotPassword;

use Yii;
use yii\base\Model;
use app\utility\IdentifyUser;

class ForgotPasswordVerificationKeyForm extends Model
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
		$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();
			
		if ($this->verificationKey !== $user->verification_key)
		{
			$this->addError($attribute, 'Wrong verification key!');
		}
	}

}
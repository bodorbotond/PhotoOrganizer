<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\utility\IdentifyUser;

class ForgotPasswordSecurityQuestionsForm extends Model
{
	public $firstAnswer;
	public $secondAnswer;

	public function rules()
	{
		return [
				// firstAnswer and secondAnswer are required
				[['firstAnswer', 'secondAnswer'], 'required'],
				// firstAnswer is validated by validateFirstAnswer()
				['firstAnswer', 'validateFirstAnswer'],
				// secondAnswer is validated by validateSecondAnswer()
				['secondAnswer', 'validateSecondAnswer'],
		];
	}

	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'firstAnswer' => 'First Answer',
				'secondAnswer' => 'Second Answer',
		];
	}
	
	public function validateFirstAnswer($attribute, $params)
	{
		
		if (strlen($this->firstAnswer) > 200)
		{
			$this->addError($attribute, 'The length of answer must be between 0 and 200 character!');
		}
			
		if (strcasecmp($this->firstAnswer, $this->getUsersSecurityAnswers()[0]->answer) !== 0)		// case-insensitive string comparision
		{
			$this->addError($attribute, 'Wrong answer!');
		}
	}
	
	public function validateSecondAnswer($attribute, $params)
	{
		
		if (strlen($this->secondAnswer) > 200)
		{
			$this->addError($attribute, 'The length of answer must be between 0 and 200 character!');
		}

		if (strcasecmp($this->secondAnswer, $this->getUsersSecurityAnswers()[1]->answer) !== 0)
		{
			$this->addError($attribute, 'Wrong answer!');
		}
	}
	
	public function getUsersSecurityAnswers()							// return user's security answers from UsersSecurityQuestions table
	{
		$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();					// return user by username or email (from session)
		return UsersSequrityQuestions::getUserSecurityAnswersByUserId($user->user_id);	// return user's security answer by user id
	}
}

<?php

namespace app\models\account;

use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\tables\SecurityQuestions;
use app\models\tables\UsersSequrityQuestions;

class SecurityQuestionsForm extends Model
{
	
	public $firstQuestion;
	public $secondQuestion;
	public $firstAnswer;
	public $secondAnswer;
	
	public function rules()
	{
		return [
			// firstQuestion, secondQuestion are required
			[['firstQuestion', 'secondQuestion', 'firstAnswer', 'secondAnswer'], 'required'],
			// secondQuestion is validated by validateSecondQuestion()
			['secondQuestion', 'validateSecondQuestion'],
			// firstAnswer is validated by validateFirstAnswer()
			['firstAnswer', 'validateFirstAnswer'],
			// secondAnswer is validated by validateSecondAnswer()
			['secondAnswer', 'validateSecondAnswer'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'firstQuestion' 	=> 'First Question',
			'secondQuestion'	=> 'Second Question',
		];
	}
	
	public function validateSecondQuestion($attribute, $params)
	{
		if ($this->firstQuestion === $this->secondQuestion)
		{
			$this->addError($attribute, 'Security questions must be different!');
		}
	}
	
	public function validateFirstAnswer($attribute, $params)
	{
		if (strlen($this->firstAnswer) > 200)
		{
			$this->addError($attribute, 'The length of answer must be between 0 and 200 character!');
		}
	}
	
	public function validateSecondAnswer($attribute, $params)
	{
		if (strlen($this->secondAnswer) > 200)
		{
			$this->addError($attribute, 'The length of answer must be between 0 and 200 character!');
		}
		
		if ($this->firstAnswer === $this->secondAnswer)
		{
			$this->addError($attribute, 'Answers must be different!');
		}
	}
	
	public function addQuestions()
	{
		if ($this->validate())
		{
			$userSecurityQuestion1 = new UsersSequrityQuestions();					// insert first question's datas
			
			$userSecurityQuestion1->user_id = Yii::$app->user->identity->user_id;
			$userSecurityQuestion1->question_id = $this->firstQuestion;
			$userSecurityQuestion1->answer = $this->firstAnswer;;
			
			$userSecurityQuestion2 = new UsersSequrityQuestions();					// insert second question's datas
			
			$userSecurityQuestion2->user_id = Yii::$app->user->identity->user_id;
			$userSecurityQuestion2->question_id = $this->secondQuestion;
			$userSecurityQuestion2->answer = $this->secondAnswer;
			
			if ($userSecurityQuestion1->save() && $userSecurityQuestion2->save())
			{
				return true;
			}
		}
		return false;
	}
	
	public function modifyQuestions()
	{
		if ($this->validate())
		{
			$userSecurityQuestionAndAnswers = UsersSequrityQuestions::getUserSecurityQuestions();
			
			// modify first question
			$userSecurityQuestionAndAnswers[0]->question_id = $this->firstQuestion;
			$userSecurityQuestionAndAnswers[0]->answer = $this->firstAnswer;
			
			//modify second question
			$userSecurityQuestionAndAnswers[1]->question_id = $this->secondQuestion;
			$userSecurityQuestionAndAnswers[1]->answer = $this->secondAnswer;
			
			if ($userSecurityQuestionAndAnswers[0]->update() || $userSecurityQuestionAndAnswers[1]->update())
			{
				return true;
			}
		}
		return false;
	}
	
	
	public function deleteQuestions()
	{
		$userSecurityQuestionAndAnswers = UsersSequrityQuestions::getUserSecurityQuestions();
		
		if ($userSecurityQuestionAndAnswers[0]->delete() && $userSecurityQuestionAndAnswers[1]->delete())
		{
			return true;
		}
		return false;
	}
	
	
	public function getQuestions()									// get all security questions from database(security_questions table)
	{
		$securityQuestionText = Array();
		$securityQuestions = SecurityQuestions::find()->all();
		
		foreach ($securityQuestions as $key=>$value)				// build array for dropDownList with security question id as
		{															//  array's ids and security question text as array's values
			$securityQuestionText[$value['question_id']] = $value['question_text'];
		}
			
		return $securityQuestionText;
	}
    
}

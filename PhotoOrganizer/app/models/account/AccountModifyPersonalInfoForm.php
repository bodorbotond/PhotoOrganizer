<?php

namespace app\models\account;

use Yii;
use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\FileValidator;
use yii\helpers\FileHelper;
use app\models\Users;

class AccountModifyPersonalInfoForm extends Model
{
	public $userName;
	public $firstName;
	public $lastName;
	public $eMail;
	public $gender;
	public $profilePicture;
	
	public function rules()
	{
		return [
				// userName, eMail, password are required
				[['userName', 'firstName', 'lastName', 'eMail', 'gender'], 'required'],
				// userName is validated by validateUserName()
				['userName', 'validateUserName'],
				// firstName is validated by validateFirstName()
				['firstName', 'validateFirstName'],
				// lastName is validated by validateLastName()
				['lastName', 'validateLastName'],
				// eMail is validated by validateEmail()
				['eMail', 'validateEmail'],
				// password is validated by validatePassword()
				[['profilePicture'],
						 'file',
						 'skipOnEmpty' => true,
						 'extensions' => 'png, jpg',
				],
		];
	}
	
	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'userName' 			=> 'User Name',
				'firstName'			=> 'First Name',
				'lastName'			=> 'Last Name',
				'eMail' 			=> 'E-mail',
				'gender'			=> 'Gender',
				'profilePicture' 	=> 'Profile Picture',
		];
	}
	
	public function validateUserName($attribute, $params)
	{
		if (Users::findByUsername($this->userName) &&
			Yii::$app->user->identity->user_name !== $this->userName)			// if exists the entered username in the database except the logged in user's username	
		{
			$this->addError($attribute, 'This username is already exists!');
		}
		
		if (strlen($this->userName) > 50)
		{
			$this->addError($attribute, 'The length of User Name must be between 0 and 50 character!');
		}
	}
	
	public function validateFirstName($attribute, $params)
	{
		if (strlen($this->firstName) > 50)
		{
			$this->addError($attribute, 'The length of First Name must be between 0 and 50 character!');
		}
	}
	public function validateLastName($attribute, $params)
	{
		if (strlen($this->lastName) > 50)
		{
			$this->addError($attribute, 'The length of Last Name must be between 0 and 50 character!');
		}
	}
	
	public function validateEmail($attribute, $params)
	{
		$validator = new EmailValidator();
		if (! $validator->validate($this->eMail, $error))
		{
			$this->addError($attribute, /*'Email is not valid!'*/$error);
		}
		
		if (Users::findByEMail($this->eMail) &&
			Yii::$app->user->identity->e_mail !== $this->eMail)
		{
			$this->addError($attribute, 'This email is already exists!');
		}
		
		if (strlen($this->eMail) > 50)
		{
			$this->addError($attribute, 'The length of E-mail must be between 0 and 50 character!');
		}
	}
	
	public function modify()
	{
		$user = Users::findOne(Yii::$app->user->identity->id);	// get logged in user
		
		if ($this->validate())								// if entered datas are validate then update user's datas
		{
			$user->user_name 		= $this->userName;
			$user->first_name		= $this->firstName;
			$user->last_name		= $this->lastName;
			$user->e_mail 			= $this->eMail;
			$user->gender			= $this->gender;
			
			if (!empty($this->profilePicture->baseName))	// if user want to modify optional profile picture then save on the server
			{
	
				if ($user->profile_picture_path !== null)		// if user has already profile picture
				{
					unlink($user->profile_picture_path);			// delete old profile picture
				}
				
				$path = 'uploads/' . $user->user_id;			// directory path in the server where the user save the photos
				$this->profilePicture->saveAs($path . '/' . $this->profilePicture->baseName . '.' . $this->profilePicture->extension);		// save new profile picture
				$user->profile_picture_path = $path . '/' . $this->profilePicture->baseName . '.' . $this->profilePicture->extension;
			}
			
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
	
	public function deleteProfilePicture()
	{
		$user = Users::findOne(Yii::$app->user->identity->id);		// get logged in user
		unlink($user->profile_picture_path);						// delete profile picture from server
		$user->profile_picture_path = null;							// profile picture path in database set null
		return $user->update();		
	}
}

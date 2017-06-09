<?php

namespace app\models;

use Yii;
use yii\validators\EmailValidator;
use yii\validators\FileValidator;
use yii\helpers\FileHelper;
use yii\base\Model;
use yii\base\Security;
use app\utility\email\SignUpVerificationSendEmail;
use app\models\Users;

class SignUpForm extends Model
{
	public $userName;
	public $firstName;
	public $lastName;
	public $eMail;
	public $password;
	public $gender;
	public $profilePicture;
	
	public function rules()
	{
		return [
				// userName, eMail, password are required
				[['userName', 'firstName', 'lastName', 'eMail', 'password', 'gender'], 'required'],
				// userName is validated by validateUserName()
				['userName', 'validateUserName'],
				// firstName is validated by validateFirstName()
				['firstName', 'validateFirstName'],
				// lastName is validated by validateLastName()
				['lastName', 'validateLastName'],
				// eMail is validated by validateEmail()
				['eMail', 'validateEmail'],
				// password is validated by validatePassword()
				['password', 'validatePassword'],
				// profilePicture must be file, not required, allowed extension
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
				'password' 			=> 'Password',
				'gender'			=> 'Gender',
				'profilePicture' 	=> 'Profile Picture',
		];
	}
	
	public function validateUserName($attribute, $params)
	{
		if (Users::findByUsername($this->userName))			// if exists the entered username in the database	
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
		
		if (Users::findByEMail($this->eMail))
		{
			$this->addError($attribute, 'This email is already exists!');
		}
		
		if (strlen($this->eMail) > 50)
		{
			$this->addError($attribute, 'The length of E-mail must be between 0 and 50 character!');
		}
	}
	
	public function validatePassword($attribute, $params)
	{
		if (strlen($this->password) < 8)
		{
			$this->addError($attribute, 'The password must be at least 8 characters!');
		}
		
		// check the password contains at least one character and at least one number and at least one special character(all characters that's not a digit or a-Z)
		if (!preg_match('/[a-zA-Z]+/', $this->password) || !preg_match('/\d+/', $this->password) || !preg_match('/[^a-zA-Z\d]+/', $this->password))
		{
			$this->addError($attribute, 'The password must be contain at least one number, one special character and one letter!');
		}
	}
	
	public function signUp()
	{
		$user = new Users();
		
		if ($this->validate())												// if entered datas are validate then insert datas to database and send email to entered email adress
		{
			$user->user_name 		= $this->userName;
			$user->first_name		= $this->firstName;
			$user->last_name		= $this->lastName;
			$user->e_mail 			= $this->eMail;
			$user->password 		= crypt($this->password, 'salt');
			$user->gender			= $this->gender;
			
			if (!empty($this->profilePicture->baseName))					// if user want to choose optional profile picture then save to the server
			{
				$path = 'uploads/' . $this->userName;						// directory path into the server where the signed up user save the profile picure
				FileHelper::createDirectory($path, 0755, false);			// parameters:	path of the directory to be created
																			//				the permission to be set for the created directory (0755 = everything for owner, read and execute for others)
																			//				whether to create parent directories if they do not exist
				$this->profilePicture->saveAs($path . '/' . $this->profilePicture->baseName . '.' . $this->profilePicture->extension);
				$user->profile_picture_path = $path . '/' . $this->profilePicture->baseName . '.' . $this->profilePicture->extension;
			}
			$user->auth_key 		= Yii::$app->security->generateRandomString(30);
			$user->account_status	= 'inactive';								// default set the user status inactive (set active by activation key in the email)
			$user->verification_key	= strval(rand(10000, 99999));				// convert a five digit number to string
			
			if($user->save())													// if user saved into database successful
			{
				if (SignUpVerificationSendEmail::sendEMail($user->e_mail, [ 'userName'			=> $user->user_name,
																			'eMail'				=> $user->e_mail,
																			'verificationKey' 	=> $user->verification_key]))
				{						// send confirmation email to users's email adress
					return true;
				}
			}
		}
		else
		{
			return false;	
		}
		
	}
}
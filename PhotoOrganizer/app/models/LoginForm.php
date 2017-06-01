<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public $accountStatus = false;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username, e_mail, password, first_name, last_name are required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        	// userStatus must be a boolean value
        	['accountStatus', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        	// userStatus is validates by validateUserStatus()
        	[['accountStatus'], 'validateAccountStatus'],
        ];
    }
    

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) 
            {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    
    
    public function validateAccountStatus($attribute, $params)
    {
    	if (!$this->hasErrors()) 
    	{
    		$user = $this->getUser();
    	
    		if (!$user || !$user->validateAccountStatus()) 
    		{
    			$this->addError('accountStatus', 'User must be activate her/his account!');
    		}
    	}
    }
    
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
    	if ($this->_user === false)
    	{
    		$this->_user = Users::findByUsername($this->username);
    	}
    
    	return $this->_user;
    }
    

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) 
        {
        	return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }
    
    
    public function loginWithTwoStepVerification($username)			// get user by username from session variable
    {
    	$user = Users::findOne(['user_name' => $username]);
    	return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
    }
    
    
    public function isActiveTwoStepVerification()			// check two step verification is active or not
    {
    	return ($this->_user->two_step_verification === 1 ? true : false);
    }
    
    
    public function updateVerificationKeyInDataBase()		// insert new verification key to database for login verification
    {
    	$user = $this->getUser();    	
    	$verificationKey = strval(rand(10000, 99999));    	 
    	$user->verification_key = $verificationKey;
    	return $user->update();    	
    }
    
    
    public function sendEmail()								// send email with login verification key to user who want to login
    {
    	$messageParams = [
    			'userName'			=> $this->_user->user_name,
    			'eMail'				=> $this->_user->e_mail,
    			'verificationKey'	=> $this->_user->verification_key,
    	];
    	$message = $this->buildMessage($messageParams);
    	
    	return Yii::$app->mailer->compose('layouts\html', ['content' => $message])
    	->setTo($this->_user->e_mail)
    	->setFrom(Yii::$app->params['adminEmail'])
    	->setSubject('Login to Photo Organizer Application')
    	->setHtmlBody($message)
    	->send();
    }
    
    
    private function buildMessage($params)					// build email HTML content
    {
    	return '
				<h1>Hi ' . $params['userName'] .',</h1>
				<div>
					<p>
						You try to login for <b>Photo Organizer</b> with ' . $params['eMail'] . ' email address! <br>
						Please confirm your login intention. Your activation key: <br>' .
    						$params['verificationKey'] .
    						'</p>' .
    						Html::a('Confirm login', Url::home('http') . 'user/loginVerification') .
    						'<p>
						<b>Photo Organizer Team</b>
					</p>
				</div>
				';
    }
    
}

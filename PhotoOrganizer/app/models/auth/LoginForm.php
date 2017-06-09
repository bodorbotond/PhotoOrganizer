<?php

namespace app\models\auth;

use Yii;
use yii\base\Model;
use app\models\Users;

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
    	$user = Users::findByUsername($username);
    	return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
    }
    
    
    public function isActiveTwoStepVerification()			// check two step verification is active or not
    {
    	return ($this->_user->two_step_verification === 1 ? true : false);
    }
    
    
    public function updateVerificationKeyInDataBase()		// insert new verification key to database for login verification
    {    	
    	$verificationKey = strval(rand(10000, 99999));    	 
    	$this->_user->verification_key = $verificationKey;
    	return $this->_user->update();    	
    }
    
}

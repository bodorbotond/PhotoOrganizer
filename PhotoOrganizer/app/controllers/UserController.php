<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\Session;
use app\utility\IdentifyUser;
use app\utility\SessionManager;
use app\utility\email\ForgotPasswordSendEmail;
use app\models\user\ChangePasswordForm;
use app\models\Users;
use app\models\LoginForm;
use app\models\LoginVerificationForm;
use app\models\SignUpForm;
use app\models\SignUpVerificationForm;
use app\models\UserIdentifyForm;
use app\models\ForgotPasswordVerificationKeyForm;
use app\models\ForgotPasswordSecurityQuestionsForm;
use app\models\ForgotPasswordOldPasswordsForm;
use app\models\UsersSequrityQuestions;
use app\models\OldPasswords;
use yii\web\yii\web;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),			// action filter
                'only' => ['logout'],							// actions
                'rules' => [
                    [											// allow authenticated users
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),				// HTTP request methods filter for each action
                												// throw an HTTP 405 error when the method is not allowed
                'actions' => [
                	'index'  				=> ['get'],
                	'login'	 				=> ['get', 'post'],
                	'loginVerification' 	=> ['get', 'post'],
                    'logout' 				=> ['post'],
                	'signUp' 				=> ['get', 'put', 'post'],
                	'signUpVerification' 	=> ['get', 'post'],
                	'usernameValidation'	=> ['get', 'post'],
                	'forgotPassword'		=> ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    // login
    

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate())		// if post request is arrived and input data are correct
        {
        	if ($model->isActiveTwoStepVerification())								// if two step verification is active
        	{
        		Yii::$app->getSession()
        			->setFlash('username', Yii::$app->request->post()['LoginForm']['username']);	// set username in session variable
        		if ($model->updateVerificationKeyInDataBase() && $model->sendEmail())				// if update verification key in database was successful and email was sent successfuly
        		{
        			$this->redirect(['/user/loginVerification']);										// redirect to verification page
        		}
        	}
        	else 																	// else (two step verification is not active)
        	{
        		if ($model->login())													// if login was successful
        		{
        			return $this->redirect(['/']);											// redirect to homepage
        		}
        	}
        }
        
        return $this->render('login', [											// when isn't post request arrived
            'model' => $model,													// then render login page
        ]);
    }
    
    
    public function actionLoginVerification()				// verification key at login when two step verification is active
    {
    	if (!Yii::$app->user->isGuest)						// if the user is logged in then he can't reached the verification page
    	{
    		return $this->goHome();
    	}
    	 
    	$model = new LoginVerificationForm();
    	 
    	if ($model->load(Yii::$app->request->post()) && $model->verification())		// if post request is arrived and verification key is correct
    	{
    		$model = new LoginForm();
    		$username = Yii::$app->getSession()->getFlash('username');				// get username from session variable
    		if ($model->loginWithTwoStepVerification($username))					// if login was successfull
    		{
    			Yii::$app->getSession()->removeFlash('username');						// remove username variable from session
    			return $this->redirect(['/']);											// redirect to homepage
    		}
    	}
    	 
    	return $this->render('loginVerification', [								// if post request isn't arrived
    			'model' => $model,												// then render the verification page
    	]);
    }

    
    // logout
    
    
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    
    // sign up
    
    
    public function actionSignUp()
    {
    	if (!Yii::$app->user->isGuest)						// if the user is logged in then can't sign up
    	{
    		return $this->goHome();
    	}
    	
    	$model = new SignUpForm();
    	
    	if ($model->load(Yii::$app->request->post()))					// when is post request
    	{
    		$model->profilePicture = UploadedFile::getInstance($model, 'profilePicture');
    		if ($model->signUp())											// when insert datas to database was successful
    		{
    			return $this->redirect(['/user/signUpVerification']);			// redirect to verification page
    		}
    	}
    	
    	return $this->render('signup', [								// if there there is no post request render the sign up page
    		'model' => $model,	
    	]);
    }
    
    
    public function actionSignUpVerification()
    {
    	if (!Yii::$app->user->isGuest)						// if the user is logged in then he can't reached the verification page
    	{
    		return $this->goHome();
    	}
    	
    	$model = new SignUpVerificationForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->verification())
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	return $this->render('signUpVerification', [
    			'model' => $model,
    	]);	
    }
    
    
    // forgot password
    
    
    public function actionUserIdentify()				// identify user by username or email for forgot password functionality 
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	$model = new UserIdentifyForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->validate())		// if exist entered username or email in database
    	{																		// save session variable username or email
    		return $this->redirect(['/user/forgotPassword']);
    	}
    	
    	return $this->render('userIdentify', [
    			'model'	=> $model,
    	]);
    }
    
    
    public function actionForgotPassword()
    {
    	if (!Yii::$app->user->isGuest)						// if the user is logged in then he can't reached forgot password page
    	{
    		return $this->goHome();
    	}									// open session
    	
    	if (SessionManager::checkSessionArray(['username', 'email']))		// if exists identified username or email
    	{
    		return $this->render('forgotPassword', []);						// render a page where the user can choose password remembering option
    	}
    	else															// else(doesn't exists identified username or email)
    	{
    		return $this->redirect(['/user/userIdentify']);					// redirect to user identify page
    	}
    }
    
    
    public function actionForgotPasswordSendToEmailAdress()				// send verification key to email adress
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))		// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);		// redirect to user identify page
    	}
    	
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();
    	
    	if (ForgotPasswordSendEmail::sendEMail($user->e_mail, [	'userName' 			=> $user->user_name,
    															'verificationKey'	=> $user->verification_key]))
    	{																// if send email this user's email adress was successful
    		return $this->redirect(['/user/forgotPassword/verificationKey']);		// redirect to verification key page
    	}
    }
    
    
    public function actionForgotPasswordSendToRecoveryEmailAdress()		// send verification key to recovery email adress
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))		// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);				// redirect to user identify page
    	}
    	
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();
    	 
    	if ($user->recovery_e_mail !== null)					// if user has recovery e-mail
    	{
	    	if (ForgotPasswordSendEmail::sendEMail($user->recovery_e_mail, ['userName' 			=> $user->user_name,
	    																	'verificationKey'	=> $user->verification_key]))
	    	{															// if send e-mail this user's recovery e-mail adress was successful
	    		return $this->redirect(['/user/forgotPassword/verificationKey']);	// redirect to verification key page
	    	}
    	}
    	else 													// else (if user has not recovery e-mail)
    	{
    		SessionManager::setSession('errorMessage', 'This account does not contain recovery e-mail adress!');	// set errorMessage
    		return $this->redirect(['/user/forgotPassword']);													// and redirect to forgotPassword page to choose another remembering password option
    	}
    }
    
    
    public function actionForgotPasswordVerificationKey()		// verification entered key when user forgot her/his password
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))	// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);				// redirect to user identify page
    	}
    	
    	$model = new ForgotPasswordVerificationKeyForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->validate())		// if entered key was correct
    	{
    		SessionManager::setSession('changePasswordPermission', true);					// set permission to change password
    		return $this->redirect(['/user/forgotPassword/changePassword']);		// redirect to change password page
    	}    	
    	
    	return $this->render('forgotPasswordVerificationKey', [
    			'model'	=> $model,
    	]);
    }
    
    
    public function actionForgotPasswordSecurityQuestions()				// answer security question when user forgot his/her password 
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))	// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);				// redirect to user identify page
    	}
    	
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();
    	
	    if (count(UsersSequrityQuestions::getUserSecurityAnswersByUserId($user->user_id)) === 0)	// if user has no security questions
	    {
			SessionManager::setSession('errorMessage', 'This account does not contain security questions!');	// set error message
			return $this->redirect(['/user/forgotPassword']);												// and redirect to forgotPassword page to choose another remembering password option
	    }
    	
    	$model = new ForgotPasswordSecurityQuestionsForm();
    	$usersSecurityQuestions = UsersSequrityQuestions::getUserSecurityQuestionsByUserId($user->user_id);		// get user's security question whiches user have to answer
    	
    	if ($model->load(Yii::$app->request->post()) && $model->validate())	// if answers was correct
    	{
    		SessionManager::setSession('changePasswordPermission', true);				// set permission to change password
    		return $this->redirect(['/user/forgotPassword/changePassword']);	// redirect to change password page
    	}
    	 
    	return $this->render('forgotPasswordSecurityQuestions', [
    			'model'					 => $model,
    			'usersSecurityQuestions' => $usersSecurityQuestions,
    	]);
    }
    
    
    public function actionForgotPasswordOldPasswords()			// password remembering option when user remember just one of her/his old passwords
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))		// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);				// redirect to user identify page
    	}
    	
    	$user = IdentifyUser::getUserFromSessionByUsernameOrEmail();
    	
    	if (count(OldPasswords::findByUserId($user->user_id)) === 0)	// if user has no old passwords
	    {
	    	SessionManager::setSession('errorMessage', 'Never changed password with this account!');		// set error message	
	    	return $this->redirect(['/user/forgotPassword']);										// and redirect to forgotPassword page to choose another remembering password option
	    }
    	
    	$model = new ForgotPasswordOldPasswordsForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->validate())		// if entered old password was correct
    	{
    		SessionManager::setSession('changePasswordPermission', true);					// set permission to change password
    		return $this->redirect(['/user/forgotPassword/changePassword']);		// redirect to change password form
    	}
    
    	return $this->render('forgotPasswordOldPasswords', [
    			'model' => $model,
    	]);
    }
    
    
    public function actionForgotPasswordChangePassword()
    {
    	if (!Yii::$app->user->isGuest)
    	{
    		return $this->goHome();
    	}
    	
    	if (!SessionManager::checkSessionArray(['username', 'email']))		// if doesn't exists identified username or email
    	{
    		return $this->redirect(['/user/userIdentify']);				// redirect to user identify page
    	}
    	
    	$model = new ChangePasswordForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->changePassword())	// if entered old password save and update Users table (new password) were successfully
    	{
    		SessionManager::deleteSessionArray(['username', 'email', 'changePasswordPermission']);		// delete session variables
    		return $this->redirect(['/user/login']);									// redirect to login page
    	}
    	
    	return $this->render('changePassword', [
    			'model' => $model,
    	]);
    }

}


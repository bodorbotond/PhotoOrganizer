<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\Alert;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\LoginVerificationForm;
use app\models\SignUpForm;
use app\models\SignUpVerificationForm;
use app\models\ProfileForm;
use app\models\app\models;

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

}


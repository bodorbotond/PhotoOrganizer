<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignUpForm;
use app\models\SignUpVerificationForm;
use app\models\ProfileForm;

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
                	'index'  	=> ['get'],
                	'login'	 	=> ['get', 'post'],
                    'logout' 	=> ['post'],
                	'signUp' 	=> ['get', 'post'],
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
        
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->redirect(['/']);
        }
        
        return $this->render('login', [
            'model' => $model,
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
    	
    	if ($model->load(Yii::$app->request->post()))			// when is post request
    	{
    		$model->profilePicture = UploadedFile::getInstance($model, 'profilePicture');
    		if ($model->signUp())								// when insert datas to database was successful
    		{
    			return $this->redirect(['/user/verification']);	// redirect to verification page
    		}
    	}
    	
    	return $this->render('signup', [						// if there is not post request render the sign up page
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
    	
    	return $this->render('signupverification', [
    			'model' => $model,
    	]);	
    }

}


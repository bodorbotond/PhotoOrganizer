<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models;
use app\models\Users;
use app\models\AccountModifyPersonalInfoForm;
use app\models\RecoveryEmailForm;
use app\models\ChangePasswordForm;
use app\models\ChangePasswordVerificationForm;

class AccountController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),			// action filter
                'only' 	=> [ 
                			'index',
                			'modifyPersonalInfo',
                			'deleteProfilePicture', 
                			'deleteAccount', 
                			'addOrModifyRecoveryEmail', 
                			'deleteRecoveryEmail', 
                			'changePassword',
                			'twoStepVerification',
                		   ],
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
                	'index'  			 		=> ['get'],
                	'modifyPersonalInfo' 		=> ['get', 'put', 'post'],
                	'addOrModifyRecoveryEmail'	=> ['get', 'put', 'post'],
                	'changePassword'			=> ['get', 'put', 'post'],
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
 
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest)						// if the user isn't logged in then he can't reached his profile page
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	return $this->render('index', []);
    }
    
    public function actionModifyPersonalInfo()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	$model = new AccountModifyPersonalInfoForm();
    	
    	if ($model->load(Yii::$app->request->post()))			// when post request is arrived
    	{
    		$model->profilePicture = UploadedFile::getInstance($model, 'profilePicture');
    		if ($model->modify())								// when modify user's datas was successful
    		{
    			return $this->redirect(['/account/index']);		// redirect to user's account info page
    		}
    	}
    	 
    	return $this->render('modify', [
    		'model' => $model,
    	]);
    }
    
    public function actionDeleteProfilePicture()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	$model = new AccountModifyPersonalInfoForm();
    	
    	if ($model->deleteProfilePicture())
    	{
	    	return $this->redirect(['/account/index']);
    	}
    }
    
    public function actionDeleteAccount()
    {
    	
    }
    
    public function actionAddOrModifyRecoveryEmail()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	$model = new RecoveryEmailForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->addOrModifyRecoveryEmail())
    	{
    		return $this->redirect(['/account/index']);
    	}
    	
    	return $this->render('addOrModifyRecoveryEmail', [
    			'model' => $model,
    	]);
    }
    
    public function actionDeleteRecoveryEmail()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	$model = new RecoveryEmailForm();
    	
    	if ($model->deleteRecoveryEmail())
    	{
    		return $this->redirect(['/account/index']);
    	}
    }
    
    public function actionChangePassword()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	$model = new ChangePasswordForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->changePassword())
    	{
    		return $this->redirect(['/account/index']);
    	}
    	
    	return $this->render('changePassword', [
    			'model' => $model,
    	]);
    }
    
    public function actionTwoStepVerification()
    {
    	if (Yii::$app->user->isGuest)
    	{
    		return $this->redirect(['/user/login']);
    	}
    	
    	if (Yii::$app->request->post())
    	{
    		$user = Users::findOne(Yii::$app->user->identity->id);
    		$user->two_step_verification = (Yii::$app->request->post('TwoStepVerificationCheckBox') ? 1 : 0);
    		if ($user->update())
    		{
	    		$this->redirect(['/account/index']);
    		}
    	}
    }

}



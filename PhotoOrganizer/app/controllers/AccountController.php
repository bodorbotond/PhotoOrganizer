<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\Users;

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
                'only' => ['index'],							// actions
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
    	if (Yii::$app->user->isGuest)						// if the user isn't logged in then he can't reached his profile page
    	{
    		return $this->goHome();
    	}
    	
    	return $this->render('index', [
    			
    	]);
    }
    
    public function actionChangeAccountPersonalInfo()
    {
    	if (Yii::$app->user->isGuest)						// if the user isn't logged in then he can't reached his profile page
    	{
    		return $this->goHome();
    	}
    	
    	$user = Users::findOne(Yii::$app->user->identity->id);
    	 
    	return $this->redirect(['/account/index']);
    }

}



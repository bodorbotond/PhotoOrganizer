<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\site\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' 	=> ['index', 'contact', 'about', 'captcha'],
                'rules' => [
                    [
                        'actions' 	=> ['index', 'contact', 'about'],
                        'allow' 	=> true,
                        'roles' 	=> ['@'],
                    ],
                	[
                		'actions' 	=> ['index', 'contact', 'about'],
                		'allow' 	=> true,
                		'roles' 	=> ['?'],
                	],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                	'index' 	=> ['get'],
                	'about'  	=> ['get'],
                	'contact' 	=> ['get', 'post'],
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
        return $this->render('index', []);
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->contact())
        {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about', []);
    }
    
    public function actionTeam()
    {
    	return $this->render('teamMember', []);
    }
}

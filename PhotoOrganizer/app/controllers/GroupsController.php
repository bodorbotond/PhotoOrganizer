<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\groups\CreateGroupForm;
use app\models\tables\Groups;

class GroupsController extends Controller
{

	
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),			// action filter
						'only' => [										// all aplied actions
								'index',
								'create',
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'create',
													],
									'roles' 	=> ['@'],						// authenticated users
								],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),				// HTTP request methods filter for each action
						// throw an HTTP 405 error when the method is not allowed
						'actions' => [
								'index'  => ['get'],
								'create' => ['get', 'put', 'post'],
						],
				],
		];
	}

	
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
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$userGroups = Groups::findByUserId(Yii::$app->user->identity->user_id);
		
		return $this->render('index', [
				'userGroups' => $userGroups,
		]);
	}
	
	
	public function actionCreate()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
	
		$model = new CreateGroupForm();
		
		if ($model->load(Yii::$app->request->post()) && $model->create())
		{
			return $this->redirect(['/groups/index']);
		}
	
		return $this->render('create', [
				'model' => $model,
		]);
	}
	
	
	public function actionViewGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
	
		$group = Groups::findByGroupId($id);
	
		$query = new Query ();
		$query->select ('u.user_name, u.profile_picture_path')					// get user's album's names and photos path which are belong to these albums
			  ->from ('users u, groups_users gu')
			  ->where ('u.user_id = gu.user_id and gu.group_id = ' . $id);
		$groupUsers = $query->all();
	
		return $this->render('viewGroup', [
				'group' 		=> $group,
				'groupUsers' 	=> $groupUsers,
		]);
	}
	
}
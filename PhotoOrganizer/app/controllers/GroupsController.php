<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\groups\CreateGroupForm;
use app\models\groups\EditGroupForm;
use app\models\tables\Groups;
use app\models\tables\GroupsUsers;
use app\models\tables\GroupsPhotos;

class GroupsController extends Controller
{

	
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),			// action filter
						'only' => [										// all aplied actions
								'index',
								'createGroup', 'editGroup', 'deleteGroup', 'viewGroup', 
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'createGroup', 'editGroup', 'deleteGroup', 'viewGroup',
													],
									'roles' 	=> ['@'],						// authenticated users
								],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),				// HTTP request methods filter for each action
						// throw an HTTP 405 error when the method is not allowed
						'actions' => [
								'index'  		=> ['get'],
								'createGroup' 	=> ['get', 'put', 'post'],
								'editGroup' 	=> ['get', 'put', 'post'],
								'deleteGroup' 	=> ['get', 'delete'],
								'viewGroup'		=> ['get', 'post'],
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
	
	
	public function actionCreateGroup()
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
	
		return $this->render('createGroup', [
				'model' => $model,
		]);
	}
	
	public function actionEditGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}

		$group = Groups::findOne($id);
		$model = new EditGroupForm($group);
	
		if ($group === null || $group->user_id !== Yii::$app->user->identity->user_id	// if id is wrong or album not belong to logged in user
			|| $model->load(Yii::$app->request->post()) && $model->edit())				// or edit album was sucessful
		{
			return $this->redirect(['/groups/index']);										// redirect to albums index page
		}
	
		return $this->render('editGroup', [
				'model' => $model,
				'group'	=> $group,
		]);
	}
	
	
	public function actionDeleteGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
	
		$group = Groups::findOne($id);
	
		if ($group === null || $group->user_id !== Yii::$app->user->identity->user_id)
		{
			return $this->redirect(['/groups/index']);
		}
		
		foreach (GroupsUsers::findByGroupId($id) as $groupUser)		// get all users whose are belong to this group
		{
			$groupUser->delete();										// delete users
		}
		foreach (GroupsPhotos::findByGroupId($id) as $groupPhoto)	// get all photos whiches are belong to this group
		{
			$groupPhoto->delete();										// delete photos
		}
		$group->delete();											// delete group
	
		return $this->redirect(['/groups/index']);
	}
	
	
	public function actionViewGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
	
		$group = Groups::findOne($id);
	
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
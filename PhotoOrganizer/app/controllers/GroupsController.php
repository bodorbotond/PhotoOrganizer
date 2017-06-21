<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\Users;
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
								'addUser',
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'createGroup', 'editGroup', 'deleteGroup', 'viewGroup',
														'addUser',
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
								'addUser'		=> ['get', 'put', 'post'],
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
		
		$userGroups = Array();		// logged in user's groups
		$otherGroups = Array();		// groups in where logged in user is a member
		foreach(GroupsUsers::findByUserId(Yii::$app->user->identity->user_id) as $groupUser)	// get all groups in where logged user is a member
		{															// GroupsUser table contain only group_id and user_id
			$group = Groups::findOne($groupUser->group_id);			// that is why have to find group by group_id
			if (Yii::$app->user->identity->user_id == $group->user_id)		// if group is belong to logged in user
			{
				array_push($userGroups, $group);
			}
			else															// else (logged in user is just a member)
			{
				array_push($otherGroups, $group);
			}
		}
		
		return $this->render('index', [
				'userGroups' 	=> $userGroups,
				'otherGroups'	=> $otherGroups,
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
			|| ($model->load(Yii::$app->request->post()) && $model->edit()))			// or edit album was sucessful
		{
			return $this->redirect(['/groups/view/' . $id]);										// redirect to albums index page
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
		
		if ($group === null || count(GroupsUsers::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) === 0)	// if id is wrong or logged in user is not a member in this group
		{
			return $this->redirect(['/groups/index']);
		}
		
		$query = new Query ();
		$query->select ('p.photo_path')								// get photos path whiches are belong to this group
			  ->from ('photos p, groups_photos gp')
			  ->where ('p.photo_id = gp.photo_id and gp.group_id = ' . $id);
		$groupPhotos = $query->all();

		$query = new Query ();
		$query->select ('u.user_name, u.profile_picture_path')		// get user's and profile_picture path whiches are belong to these group
			  ->from ('users u, groups_users gu')
			  ->where ('u.user_id = gu.user_id and gu.group_id = ' . $id);
		$groupUsers = $query->all();
		
		$administrator = Users::findOne($group->user_id);
	
		return $this->render('viewGroup', [
				'group' 		=> $group,
				'groupPhotos'	=> $groupPhotos,
				'groupUsers' 	=> $groupUsers,
				'administrator'	=> $administrator,
		]);
	}
	
	
	public function actionAddUser($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		if (Users::findOne($id) === null) 
		{
			return $this->redirect(['/groups/index']);
		}
		
		if (Yii::$app->request->isPost)		// if post request arrive
		{
			$group = Groups::findOne(Yii::$app->request->post('GroupId'));
			
			if ($group === null || $group->user_id !== Yii::$app->user->identity->user_id)	// if group id what arrived in post is wrong or group is not belong to logged in user
			{
				return $this->redirect(['/groups/index']);
			}
			
			if (count(GroupsUsers::findByGroupIdAndUserId($group->group_id, $id)) === 0)	// if user is not a member in this group yet
			{
				$groupUser = new GroupsUsers();
				$groupUser->group_id = $group->group_id;
				$groupUser->user_id = $id;
				
				if ($groupUser->save())
				{
					return $this->redirect(['/groups/view/' . $group->group_id]);
				}
			}
		}
		
		return $this->redirect(['/search/users/view/' . $id]);
	}
	
}
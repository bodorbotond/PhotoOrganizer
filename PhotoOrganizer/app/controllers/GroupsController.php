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
use app\models\tables\GroupNotifications;
use app\utility\email\GroupMemberSendEmail;

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
								'addUser', 'joinGroup',
								'leaveGroup',
								'remove',
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'createGroup', 'editGroup', 'deleteGroup', 'viewGroup',
														'addUser', 'joinGroup',
														'leaveGroup',
														'remove',
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
								'joinGroup'		=> ['get', 'put', 'post'],
								'leaveGroup'	=> ['get', 'delete', 'post'],
								'remove'		=> ['get', 'delete', 'post'],
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
	
	
	// basic functionality with groups (create, edit, delete, view)
	
	
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
	
		if ($group === null || $group->user_id !== Yii::$app->user->identity->user_id)	// if id is wrong or group not belong to logged in user
		{
			return $this->redirect(['/groups/index']);										// redirect to groups index page
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->edit())					// if edit group was sucessful
		{
			return $this->redirect(['/groups/view/' . $id]);								// redirect to group view page
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
		
		foreach (GroupsUsers::findByGroupId($id) as $groupUser)		// delete all users whose are belong to this group
		{
			$groupUser->delete();
		}
		
		foreach (GroupsPhotos::findByGroupId($id) as $groupPhoto)	// delete all photos whiches are belong to this group
		{
			$groupPhoto->delete();
		}
		
		$group->delete();											// delete group
	
		return $this->redirect(['/groups/index']);
	}
	
	
	public function actionViewGroup($id)
	{
		$group = Groups::findOne($id);
		
		if ($group === null)	// if group id is wrong
		{
			return $this->redirect(['/groups/index']);
		}
		
		$administrator = Users::findOne($group->user_id);
		$isNotification = false;								// has a notification for signed up user who wanted to join a group
		
		if (!Yii::$app->user->isGuest)							// if user is not guest have to decide logged in user is group's administrator or not
		{
			$isAdministrator = $administrator->user_id === Yii::$app->user->identity->user_id;
			$isMember = count(GroupsUsers::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) === 1 ? true : false;
			$isNotification = count(GroupNotifications::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) === 1 ? true : false;
		}
		
		$query = new Query ();
		$query->select ('u.user_name, p.photo_path, p.photo_visibility, p.photo_tag, p.photo_title, p.photo_description')	// get user's photos path whiches are belong to this group
			  ->from ('users u, photos p, groups_photos gp')																// in group can be only public photos
			  ->where ('u.user_id = p.user_id and p.photo_id = gp.photo_id and gp.group_id = ' . $id);
		$groupPublicPhotos = $query->all();

		$query = new Query ();
		$query->select ('u.user_id, u.user_name, u.profile_picture_path')		// get user's and profile_picture path whiches are belong to these group
			  ->from ('users u, groups_users gu')
			  ->where ('u.user_id = gu.user_id and gu.group_id = ' . $id);
		$groupUsers = $query->all();
		
		$query = new Query ();
		$query->select ('u.user_id, u.user_name, u.profile_picture_path')		// get user's whiches wanted to join this group
			  ->from ('users u, group_notifications gn')
			  ->where ('u.user_id = gn.user_id and gn.group_id = ' . $id);
		$usersWithJoinIntension = $query->all();
		
		$photosNumber = count($groupPublicPhotos);
		$usersNumber = count($groupUsers);		
		
		//render view files by logged in user status(administrator, member, other logged in user or guest)
		
		if (!Yii::$app->user->isGuest)		// if user is not guest
		{
			if ($isAdministrator)				// if user is administrator
			{
				return $this->render('viewGroupForAdministrator', [
						'group' 					=> $group,
						'administrator'				=> $administrator,
						'photosNumber'				=> $photosNumber,
						'usersNumber'				=> $usersNumber,
						'groupPublicPhotos' 		=> $groupPublicPhotos,
						'groupUsers'				=> $groupUsers,
						'usersWithJoinIntension'	=> $usersWithJoinIntension,
				]);
			}
			else if($isMember)					// if user is member
			{
				return $this->render('viewGroupForMembers', [
						'group' 				=> $group,
						'administrator'			=> $administrator,
						'photosNumber'			=> $photosNumber,
						'usersNumber'			=> $usersNumber,
						'groupPublicPhotos' 	=> $groupPublicPhotos,
						'groupUsers'			=> $groupUsers,
				]);
			}
			else								// if user is just signed up user
			{
				return $this->render('viewGroupForUsers', [		// noone can view public or private photos
					'group' 			=> $group,
					'administrator'		=> $administrator,
					'photosNumber'		=> $photosNumber,
					'usersNumber'		=> $usersNumber,
					'isNotification'	=> $isNotification,
					'groupPublicPhotos' => ($group->group_visibility === 'private' ?  Array() : $groupPublicPhotos),
					'groupUsers'		=> ($group->group_visibility === 'private' ? Array() : $groupUsers),
				]);
			}
		}
		else								// if user is guest
		{
			return $this->render('viewGroupForGuests', [		// noone can view public or private photos
					'group' 			=> $group,
					'administrator'		=> $administrator,
					'photosNumber'		=> $photosNumber,
					'usersNumber'		=> $usersNumber,
					'groupPublicPhotos' => ($group->group_visibility == 'private' ?  Array() : $groupPublicPhotos),
					'groupUsers'		=> ($group->group_visibility === 'private' ? Array() : $groupUsers),
			]);
		}
	
	}
	
	
	// add user to a group or join to a group
	
	
	public function actionAddUser($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$addedUser = Users::findOne($id);
		
		if ($addedUser === null) 
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
				
				if ($groupUser->save())															// if user added to group successfuly
				{
					if (GroupMemberSendEmail::sendEMail($addedUser->e_mail,
					'You become a group member!', 'addUser', [			// if added receive notification email
																'userName' 			=> $addedUser->user_name,
																'administratorName'	=> Users::findOne($group->user_id)->user_name,
																'groupName'			=> $group->group_name,
																'groupVisibility'	=> $group->group_visibility,
																'groupId'			=> $group->group_id,
												   			  ])
					)	
					{
						if (count(GroupNotifications::findByGroupIdAndUserId($group->group_id, $id)) !== 0)	// if added user has already a join intension (notification)
						{
							$groupNotification = GroupNotifications::findByGroupIdAndUserId($group->group_id, $id)[0];
							$groupNotification->delete();													// delete this intension (notification)	
						}
						
						return $this->redirect(['/groups/view/' . $group->group_id]);
					}
				}
			}
		}
		
		return $this->redirect(['/search/users/view/' . $id]);
	}
	
	
	public function actionJoinGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$group = Groups::findOne($id);
		
		if ($group === null)		// if group id is wrong
		{
			return $this->redirect(['/groups/index']);
		}
		
		$administrator = Users::findOne($group->user_id);
		
		if (count(GroupsUsers::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) !== 0	// if user is already a member in this group
			|| $administrator->user_id === Yii::$app->user->identity->user_id)							// or this group is belong to logged in user
		{
			return $this->redirect(['/groups/view/' . $group->group_id]);
		}
		else 
		{
			if (count(GroupNotifications::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) === 0)	// if user who want to join has no already a join intension (notification)
			{
				$groupNotification = new GroupNotifications();
				$groupNotification->group_id = $id;
				$groupNotification->user_id = Yii::$app->user->identity->user_id;
				$groupNotification->notification_text = 'Join to group';
				
				if ($groupNotification->save())				// save notification
				{
					GroupMemberSendEmail::sendEMail($administrator->e_mail,		// send notification email
					'New member in your group!', 'joinUser', [
																'userName' 			=> Yii::$app->user->identity->user_name,
																'administratorName'	=> $administrator->user_name,
																'groupName'			=> $group->group_name,
																'groupVisibility'	=> $group->group_visibility,
																'groupId'			=> $group->group_id,
															  ]);
				}
			}

			return $this->redirect(['/groups/view/' . $group->group_id]);
		}
	}
	
	
	// leave group
	
	
	public function actionLeaveGroup($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$group = Groups::findOne($id);
		
		if ($group === null)	// if group id is wrong 
		{
			return $this->redirect(['/groups/index']);	// redirect to groups index page
		}
		
		$administrator = Users::findOne($group->user_id);
		$groupUser = GroupsUsers::findOneByUserId(Yii::$app->user->identity->user_id);

		if ($groupUser !== null && $group->user_id !== Yii::$app->user->identity->user_id)		//if logged in user is group member and not administrator
		{
			if($groupUser->delete())		// if user was removed from group successfuly
			{
				GroupMemberSendEmail::sendEMail($administrator->e_mail, 'Leave Your Group!',
				'leaveGroup', [
								'administratorName' => $administrator->user_name,
								'userName'			=> Yii::$app->user->identity->user_name,
								'groupName'			=> $group->group_name,
								'groupVisibility'	=> $group->group_visibility,
				]);
			}
		}
		
		return $this->redirect(['/groups/view/' . $id]);
	}
	
	
	// remove from group
	
	
	public function actionRemove($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$group = Groups::findOne($id);
		
		if ($group === null || $group->user_id !== Yii::$app->user->identity->user_id)		// if group id is wrong or this group is not belong to logged in user
		{
			return $this->redirect(['/groups/index']);
		}
		
		$administrator = Users::findOne($group->user_id);
		
		if (Yii::$app->request->isPost)		// if post request arrive
		{
			//remove selected photos
			
			$query = new Query ();
			$query->select('p.photo_path, p.photo_id')		// get user's photos path whiches are belong to this album
				 ->from ('photos p, groups_photos gp')
				 ->where ('p.photo_id = gp.photo_id and gp.group_id = ' . $id);
			$photosInGroups = $query->all();
				
			foreach ($photosInGroups as $photo)
			{
				// in check box name is not allowed . character =>
				// that is why . character must replace with _ character
				if (Yii::$app->request->post(str_replace('.', '_', $photo['photo_path'])))
				{
					$groupPhoto = GroupsPhotos::findOneByPhotoId($photo['photo_id']);
					$groupPhoto->delete();
				}
			}
			
			//remove selected users
			
			$query = new Query ();
			$query->select('u.user_id, u.user_name, u.e_mail')		// get user's photos path whiches are belong to this album
				  ->from ('users u, groups_users gu')
				  ->where ('u.user_id = gu.user_id and gu.group_id = ' . $id);
			$usersInGroups = $query->all();
			
			foreach ($usersInGroups as $user)
			{
				$selectedUserId = Yii::$app->request->post($user['user_id']);
				if ($selectedUserId && $selectedUserId !== $group->user_id)			// if any user id in group equal
				{																	// equal to selected user id
					$groupUser = GroupsUsers::findOneByUserId($user['user_id']);	// except the administrator id
					if ($groupUser->delete())
					{
						GroupMemberSendEmail::sendEMail($user['e_mail'], 'Remove From Group',
						'removeUser', [
										'userName' 			=> $user['user_name'],
										'administratorName'	=> $administrator->user_name,
										'groupName'			=> $group->group_name,
										'groupVisibility'	=> $group->group_visibility,
						]);
					}
				}
			}
			
			
		}
		
		return $this->redirect(['/groups/view/' . $id]);
	}
	
}
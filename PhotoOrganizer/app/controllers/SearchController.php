<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\search\SearchUserForm;
use app\models\Users;
use app\models\tables\Albums;
use app\models\tables\Groups;
use app\models\tables\GroupsUsers;
use app\models\tables\Photos;
use app\models\search\app\models\search;

class SearchController extends Controller
{
	
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' 	=> [
                			'index',
                			'search',
                			'viewUser', 'viewAlbum', 'viewPhoto',
                			'searchUser',
                		   ],
                'rules' => [
                    [
                        'actions' 	=> [
                        				'index',
			                			'search',
			                			'viewUser', 'viewAlbum', 'viewPhoto',
                        				'searchUser',
                        			   ],
                        'allow' 	=> true,
                        'roles' 	=> ['@'],
                    ],
                	[
                		'actions' 	=> [
                						'index',
			                			'search',
			                			'viewUser', 'viewAlbum', 'viewPhoto',
                						'searchUser',
                					   ],
                		'allow' 	=> true,
                		'roles' 	=> ['?'],
                	],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                				'index' 		=> ['get'],
                				'search' 		=> ['get', 'post'],
                				'viewUser'		=> ['get'],
                		 		'viewAlbum'		=> ['get'],
                		 		'viewPhoto'		=> ['get'],
                				'searchUser' 	=> ['get', 'post'],
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
        return $this->render('index', []);
    }
    
    
    // search
    
    
    public function actionSearch()
    {    	
    	if (Yii::$app->request->isPost)
    	{
    		$searchText = Yii::$app->request->post('SearchText');
    		
    		if ($searchText !== '')			// if search text is not empty
    		{    		
	    		$query = 'select * from `Users` where `user_name` like \'%' . $searchText . '%\'';
	    		$users = Users::findBySql($query)->all();
	    		
	    		$query = 'select * from `Albums` where `album_name` like \'%' . $searchText . '%\'';
	    		$albums = Albums::findBySql($query)->all();
	    		
	    		$query = 'select * from `Groups` where `group_name` like \'%' . $searchText . '%\'';
	    		$groups = Groups::findBySql($query)->all();
	    		
	    		return $this->render('index',[
	    				'users' 	=> $users,
	    				'albums' 	=> $albums,
	    				'groups' 	=> $groups,
	    		]);
    		}
    	}
    	
    	return $this->goHome();
    }
    
    
    // view search result
    
    
    public function actionViewUser($id)
    {
    	$user = Users::findOne($id);
    	
    	if ($user === null)				// if id is wrong
    	{
    		return $this->goHome();			// redirect to home page
    	}
    	
    	$groups = Array();			
    	if (!Yii::$app->user->isGuest)		// when user is logged id
    	{
    		foreach(Groups::findByUserId(Yii::$app->user->identity->user_id) as $group)		// build group list with group's name and id for 'add user to group' functionality
	    	{
	    		$groups[$group->group_id] = $group->group_name;
	    	}
    	}
    	
    	$userPhotos = Photos::findByUserId($id);
    	
    	return $this->render('viewUser',[
    			'user' 			=> $user,
    			'userPhotos'	=> $userPhotos,
    			'groups'		=> $groups,
    	]);
    }
    
    
    public function actionViewAlbum($id)
    {
    	$album = Albums::findOne($id);
    	
    	if ($album === null)
    	{
    		return $this->goHome();
    	}
    	
    	$user = Users::findOne($album->user_id);
    	
    	$query = new Query ();
    	$query->select ('p.photo_path, p.photo_visibility')					// get user's photos path whiches are belong to this album
    		  ->from ('photos p, albums_photos ap')
    		  ->where ('p.photo_id = ap.photo_id and ap.album_id = ' . $id);
    	$albumPhotos = $query->all();
    	 
    	return $this->render('viewAlbum',[
    			'album' 		=> $album,
    			'user'			=> $user,
				'albumPhotos' 	=> $albumPhotos,
    	]);
    }
    
    
    public function actionViewGroup($id)
    {
    	$group = Groups::findOne($id);
    	
    	if ($group === null)
    	{
    		return $this->goHome();
    	}
    	
    	$user = Users::findOne($group->user_id);
    	
    	$query = new Query ();
    	$query->select ('p.photo_path, p.photo_visibility')								// get photos path whiches are belong to this group
    		  ->from ('photos p, groups_photos gp')
    		  ->where ('p.photo_id = gp.photo_id and gp.group_id = ' . $id);
    	$groupPhotos = $query->all();
    	
    	$query = new Query ();
    	$query->select ('u.user_name, u.profile_picture_path')		// get user's and profile_picture path whiches are belong to these group
    		  ->from ('users u, groups_users gu')
    		  ->where ('u.user_id = gu.user_id and gu.group_id = ' . $id);
    	$groupUsers = $query->all();
    	
    	if (!Yii::$app->user->isGuest)		// when user is logged id
    	{
    		$isMember = count(GroupsUsers::findByGroupIdAndUserId($id, Yii::$app->user->identity->user_id)) !== 0;
    	}
    	 
    	return $this->render('viewGroup',[
    			'group' 		=> $group,
    			'user'			=> $user,
				'groupPhotos'	=> $groupPhotos,
				'groupUsers' 	=> $groupUsers,
    			'isMember'		=> $isMember,
    	]);
    }
    
    
    public function actionSearchUser()
    {
    	$model = new SearchUserForm();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->validate())
    	{
    		return $this->render('index', [
    			'users' 	=> $model->searchUser(),
    			'albums'	=> [],
    			'groups'	=> [],
    		]);
    	}
    	
    	return $this->render('searchUser',[
    			'model' => $model,
    	]);
    }

}

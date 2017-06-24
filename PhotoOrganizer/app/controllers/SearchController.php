<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\search\SearchUserForm;
use app\models\search\SearchGroupForm;
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
                			'searchUser',
                			'searchGroup',
                			'viewUser',
                		   ],
                'rules' => [
                    [
                        'actions' 	=> [
                        				'index',
			                			'search',
			                			'searchUser',
                						'searchGroup',
                        				'viewUser'
                        			   ],
                        'allow' 	=> true,
                        'roles' 	=> ['@'],
                    ],
                	[
                		'actions' 	=> [
                						'index',
			                			'search',
			                			'searchUser',
                						'searchGroup',
                						'viewUser'
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
                				'searchUser' 	=> ['get', 'post'],
                				'searchGroup'	=> ['get', 'post'],
                				'viewUser'		=> ['get'],
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
    
    
    public function actionSearchGroup()
    {
    	$model = new SearchGroupForm();
    	 
    	if ($model->load(Yii::$app->request->post()) && $model->validate())
    	{
    		return $this->render('index', [
    				'users' 	=> [],
    				'albums'	=> [],
    				'groups'	=> $model->searchGroup(),
    		]);
    	}
    	 
    	return $this->render('searchGroup',[
    			'model' => $model,
    	]);
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

}

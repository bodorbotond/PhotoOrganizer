<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\albums\CreateAlbumForm;
use app\models\tables\Albums;

class AlbumsController extends Controller
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
								'index' 	=> ['get'],
								'create' 	=> ['get', 'put', 'post'],
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
		
		$userAlbums = Albums::findByUserId(Yii::$app->user->identity->user_id);
		
		return $this->render('index', [
				'userAlbums' => $userAlbums,
		]);
	}
	
	
	public function actionCreate()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$model = new CreateAlbumForm();
		
		if ($model->load(Yii::$app->request->post()) && $model->create())
		{
			return $this->redirect(['/albums/index']);
		}
		
		return $this->render('create', [
				'model' => $model,
		]);
	}
	
	
	public function actionViewAlbum($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$album = Albums::findByAlbumId($id);
		
		$query = new Query ();
		$query->select ('p.photo_path')					// get user's album's names and photos path which are belong to these albums
		 	  ->from ('photos p, albums_photos ap')
		 	  ->where ('p.photo_id = ap.photo_id and ap.album_id = ' . $id);
		 $albumPhotos = $query->all();
		
		return $this->render('viewAlbum', [
				'album' 		=> $album,
				'albumPhotos' 	=> $albumPhotos,
		]);
	}
	
}
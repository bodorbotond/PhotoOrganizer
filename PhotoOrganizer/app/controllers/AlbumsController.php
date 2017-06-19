<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\albums\CreateAlbumForm;
use app\models\albums\EditAlbumForm;
use app\models\tables\Photos;
use app\models\tables\Albums;
use app\models\tables\AlbumsPhotos;

class AlbumsController extends Controller
{

	
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),			// action filter
						'only' => [										// all aplied actions
								'index',
								'createAlbum', 'editAlbum', 'deleteAlbum', 'viewAlbum',
								'removePhotos',
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'createAlbum', 'editAlbum', 'deleteAlbum', 'viewAlbum',
														'removePhotos',
													],
									'roles' 	=> ['@'],						// authenticated users
								],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),				// HTTP request methods filter for each action
						// throw an HTTP 405 error when the method is not allowed
						'actions' => [
								'index' 		=> ['get'],
								'createAlbum' 	=> ['get', 'put', 'post'],
								'editAlbum'		=> ['get', 'put', 'post'],
								'deleteAlbum'	=> ['get', 'delete'],
								'viewAlbum'		=> ['get', 'post'],
								'removePhotos'	=> ['get', 'delete', 'post'],
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
		
		$userAlbums = Albums::findByUserId(Yii::$app->user->identity->user_id);		// get user's albums
		
		return $this->render('index', [
				'userAlbums' => $userAlbums,
		]);
	}
	
	
	public function actionCreateAlbum()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$model = new CreateAlbumForm();
		
		if ($model->load(Yii::$app->request->post()) && $model->create())		// if create album was sucessful
		{
			return $this->redirect(['/albums/index']);								// redirect to albums index page
		}	
		
		return $this->render('createAlbum', [
				'model' => $model,
		]);
	}
	
	
	public function actionEditAlbum($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}

		$album = Albums::findOne($id);
		$model = new EditAlbumForm($album);
	
		if ($album === null || $album->user_id !== Yii::$app->user->identity->user_id	// if id is wrong or album not belong to logged in user
			|| $model->load(Yii::$app->request->post()) && $model->edit())				// or edit album was sucessful
		{
			return $this->redirect(['/albums/index']);										// redirect to albums index page
		}
	
		return $this->render('editAlbum', [
				'model' => $model,
				'album'	=> $album,
		]);
	}
	
	
	public function actionDeleteAlbum($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
	
		$album = Albums::findOne($id);
		
		if ($album === null || $album->user_id !== Yii::$app->user->identity->user_id)
		{
			return $this->redirect(['/albums/index']);
		}
		
		foreach(AlbumsPhotos::findByAlbumId($id) as $albumPhoto)	// get all photos whiches belong to this album
		{
			$albumPhoto->delete();										// delete these photos
		}
		$album->delete();											// delete album
	
		return $this->redirect(['/albums/index']);
	}
	
	
	public function actionViewAlbum($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$album = Albums::findByAlbumId($id);
		
		if ($album === null || $album->user_id !== Yii::$app->user->identity->user_id)	// if id is wrong or this album not belong to logged in user
		{
			return $this->redirect(['/albums/index']);
		}
		
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
	
	
	// working with selected photos
	
	
	public function actionRemovePhotos($id)
	{
	
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$album = Albums::findOne($id);
	
		if (count(Yii::$app->request->post()) !== 0)		// if there are selected photo with post request
		{
			foreach (Photos::findByUserId(Yii::$app->user->identity->user_id) as $photo)		// get all logged in user's photos
			{
				// in check box name is not allowed . character =>
				// that is why . character must replace with _ character
				if (Yii::$app->request->post(str_replace('.', '_', $photo->photo_path)))
				{	
					$albumsPhoto = AlbumsPhotos::findOneByAlbumIdAndPhotoId($album->album_id, $photo->photo_id);
					$albumsPhoto->delete();					
				}
			}
		}
	
		$this->redirect(['/albums/view/' . $id]);
	}
	
}
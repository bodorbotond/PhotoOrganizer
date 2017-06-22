<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\db\Query;
use app\models\Users;
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
	
	
	// basic function with albums (create, edit, delete, view)
	
	
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
	
		if ($album === null || $album->user_id !== Yii::$app->user->identity->user_id)	// if id is wrong or album not belong to logged in user
		{
			return $this->redirect(['/albums/index']);										// redirect to albums index page
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->edit())					// if edit album was sucessful
		{
			return $this->redirect(['/albums/view/' . $id]);								// redirect to view album page
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
		
		if ($album === null || $album->user_id !== Yii::$app->user->identity->user_id)	// if id is wrong or album not belong to logged in user
		{
			return $this->redirect(['/albums/index']);										// redirect to albums index page
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
		$album = Albums::findOne($id);							// get album from database by album id
		
		if ($album === null)	// if id is wrong or album not belong to logged in user
		{
			return $this->redirect(['/albums/index']);	// redirect to albums index page
		}
		
		$administrator = Users::findOne($album->user_id);		// get album owner
		
		if (!Yii::$app->user->isGuest)							// if user is not guest have to decide logged in user is album's administrator or not
		{
			$isAdministrator = $administrator->user_id === Yii::$app->user->identity->user_id;
		}
		
		$query = new Query ();
		$query->select ('p.photo_path, p.photo_visibility, p.photo_tag, p.photo_title, p.photo_description')		// get user's photos path whiches are belong to this album
		 	  ->from ('photos p, albums_photos ap')
		 	  ->where ('p.photo_id = ap.photo_id and ap.album_id = ' . $id);
		$albumPhotos = $query->all();
		
		$photosNumber = count($albumPhotos);
		 
		$albumPrivatePhotos = Array ();
		$albumPublicPhotos	= Array ();
		
		foreach ($albumPhotos as $photo)	// separate public and private photos 
		{ 
			if ($photo['photo_visibility'] === 'private')
			{
				array_push($albumPrivatePhotos, $photo);
			}
			else 
			{
				array_push($albumPublicPhotos, $photo);
			}
		}
		
		if (!Yii::$app->user->isGuest)							// if user is not guest have to decide logged in user is album's administrator or not
		{
			if ($isAdministrator)		// render view page by guest or owner user
			{
				return $this->render('viewAlbumForAdministrator', [
						'album' 				=> $album,
						'administrator'			=> $administrator,
						'photosNumber'			=> $photosNumber,
						'albumPrivatePhotos' 	=> $albumPrivatePhotos,
						'albumPublicPhotos' 	=> $albumPublicPhotos,
				]);
			}
		}
		
		if ($album->album_visibility === 'private')		// if album is private
		{
				return $this->render('viewAlbumForOthers', [		// noone can view public or private photos
						'album' 				=> $album,
						'administrator'			=> $administrator,
						'photosNumber'			=> $photosNumber,
						'albumPublicPhotos' 	=> Array(),
				]);
		}
		else 										// else (if album is public)
		{
				return $this->render('viewAlbumForOthers', [		// pass public photos to view page
						'album' 				=> $album,
						'administrator'			=> $administrator,
						'photosNumber'			=> $photosNumber,
						'albumPublicPhotos' 	=> $albumPublicPhotos,
				]);
		}
		
	}
	
	
	// working with selected photos
	
	
	public function actionRemovePhotos($id)
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
	
		if (Yii::$app->request->isPost)		// if post request arrive
		{
			
			$query = new Query ();
			$query->select('p.photo_path, p.photo_id')		// get user's photos path whiches are belong to this album
				  ->from ('photos p, albums_photos ap')
				  ->where ('p.photo_id = ap.photo_id and ap.album_id = ' . $id);
			$userPhotosInAlbum = $query->all();
			
			foreach ($userPhotosInAlbum as $photo)
			{
				// in check box name is not allowed . character =>
				// that is why . character must replace with _ character
				if (Yii::$app->request->post(str_replace('.', '_', $photo['photo_path'])))
				{	
					$albumsPhoto = AlbumsPhotos::findOneByPhotoId($photo['photo_id']);
					$albumsPhoto->delete();					
				}
			}
		}
	
		$this->redirect(['/albums/view/' . $id]);
	}
	
}
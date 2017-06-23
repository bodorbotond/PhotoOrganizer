<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\tables\Photos;
use app\models\photos\PhotoUploadForm;
use app\models\photos\EditPhotoForm;
use app\models\tables\Albums;
use app\models\tables\AlbumsPhotos;
use app\models\tables\Groups;
use app\models\tables\GroupsUsers;
use app\models\tables\GroupsPhotos;
use app\models\tables\app\models\tables;

class PhotosController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),			// action filter
						'only' => [										// all aplied actions
								'index', 
								'showByExtension', 'showBySize', 'showByVisibility', 'showByUploadDate',
								'select',
								'editOnePhoto',
								'editMorePhoto',
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,				// allow
									'actions'	=> [					// these actions
														'index',
														'showByExtension', 'showBySize', 'showByVisibility', 'showByUploadDate',
														'select',
														'editOnePhoto',
														'editMorePhoto',
													],
									'roles' 	=> ['@'],						// authenticated users
								],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),				// HTTP request methods filter for each action
						// throw an HTTP 405 error when the method is not allowed
						'actions' => [
								'index'  			=> ['get', 'put', 'post'],
								'showByExtension' 	=> ['get'],
								'showBySize'		=> ['get'],
								'showByVisibility' 	=> ['get'],
								'showByUploadDate' 	=> ['get'],
								'select'			=> ['get', 'put', 'post'],
								'editOnePhoto'		=> ['get', 'put', 'post'],
								'editMorePhoto'		=> ['get', 'put', 'post'],
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
		if (Yii::$app->user->isGuest)			// if the user isn't logged in then he/she can't reached the photos page
		{
			return $this->redirect(['/user/login']);
		}
		
		$userPhotos = Photos::findByUserId(Yii::$app->user->identity->user_id);		// if user has no photos => photo upload form, otherwise user's photos appear
		
		$userAlbums = Array();
		foreach(Albums::findByUserId(Yii::$app->user->identity->user_id) as $album)		// build album list for add to album menu item from Add To dropdown menu item
		{
			array_push($userAlbums, [
					'label' 	=> '<div class="dropDownButton">' . $album->album_name . '</div>',											// label(menu item's name) = album name
					'encode' 	=> false,																									// encode html elements = false
					'options' 	=> ['onclick' => 'submitAddToForm(\'' . Url::home('http') . '\', \'ata\', \'' . $album->album_id . '\')']	// onclick event = javascript submitAddToForm(url, action, id) function
			]);																																//	(this function will redirect to selectAddTo($a, $id) function)
		}
		
		$userGroups = Array();															// build group list for add to group menu item from Add To dropdown menu item (groups in where logged in user is a member)
		foreach (GroupsUsers::findByUserId(Yii::$app->user->identity->user_id) as $groupUser)
		{															// GroupsUser table contain only group_id and user_id
			$group = Groups::FindOne($groupUser->group_id);			// that is why have to find group by group_id
			array_push($userGroups, [
					'label' 	=> '<div class="dropDownButton">' . $group->group_name . '</div>',											// label(menu item's name) = group name
					'encode' 	=> false,																									// encode html elements = false
					'options' 	=> ['onclick' => 'submitAddToForm(\'' . Url::home('http') . '\', \'atg\', \'' . $group->group_id . '\')']	// onclick event = javascript submitAddToForm(url, action, id) function																															//	(this function will redirect to selectAddTo($a, $id) function)
			]);
		}
		
		$model = new PhotoUploadForm();
		
		if ($model->load(Yii::$app->request->post()))					// if post request is arrived
		{
			$model->photos = UploadedFile::getInstances($model, 'photos');		// get instance of uploaded photos
			if ($model->upload())												// insert photos to database
			{
				return $this->redirect(['/photos/index']);
			}
		}
		
		return $this->render('index', [
				'model'		 => $model,
				'userPhotos' => $userPhotos,
				'userAlbums' => $userAlbums,
				'userGroups' => $userGroups,
		]);
	}
	
	
	//show photos by different condition
	
	
	public function actionShowByExtension()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		if (count(Photos::findByUserId(Yii::$app->user->identity->user_id)) === 0)	// if user hasn't any photos
		{
			$this->redirect(['/photos/index']);											// redirect to upload photos page
		}
		
		$showBy = 'Extension';
		$jpgPhotos = Photos::findByExtension('jpg');
		$pngPhotos = Photos::findByExtension('png');
		
		
		return $this->render('showBy', [
				'showBy' 	=> $showBy,
				'jpgPhotos' => $jpgPhotos,
				'pngPhotos'	=> $pngPhotos,
		]);
	}
	
	
	public function actionShowBySize()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$showBy = 'Size';
		$photos = Photos::findOrderBy('photo_size');
		
		return $this->render('showBy', [
				'showBy' => $showBy,
				'photos' => $photos,
		]);
	}
	
	
	public function actionShowByVisibility()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$showBy = 'Visibility';
		$privatePhotos = Photos::findByVisibility('private');
		$publicPhotos = Photos::findByVisibility('public');
		
		return $this->render('showBy', [
				'showBy' 		=> $showBy,
				'privatePhotos' => $privatePhotos,
				'publicPhotos' 	=> $publicPhotos,
		]);
	}
	
	
	public function actionShowByUploadDate()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$showBy = 'Upload Date';
		$photos2017 = Photos::findBetweenTwoDate('2017-01-01', '2017-12-31');
		
		return $this->render('showBy', [
				'showBy' 	 => $showBy,
				'photos2017' => $photos2017,
		]);
	}
	
	
	// working with selected photos
	
	
	public function actionSelect($a)
	{

		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		if (count(Yii::$app->request->post()) !== 0)		// if there are selected photo with post request
		{
			foreach (Photos::findByUserId(Yii::$app->user->identity->user_id) as $photo)		// get all logged in user's photos
			{
				// in check box name is not allowed . character => 
				// that is why . character must replace with _ character
				if (Yii::$app->request->post(str_replace('.', '_', $photo->photo_path)))
				{

					if ($a === 'pr')			// if action == set photos visibility to private
					{
						if ($photo->photo_visibility !== 'private')		// if photo's visibility is not equal yet private
						{
							$photo->photo_visibility = 'private';			// set to private
							$photo->update();
						}
					}
					
					if ($a === 'pb')			// if action == set photos visibility to public
					{
						if ($photo->photo_visibility !== 'public')		// if photo's visibility is not equal yet public
						{
							$photo->photo_visibility = 'public';			// set to public
							$photo->update();
						}
					}
					
					if ($a === 'd')				// if action == delete photos
					{
						$photo->delete();				// delete from database
						unlink($photo->photo_path);		// delete from server
					}
					
					if ($a === 'e')				// if action == edit photos
					{
						return $this->redirect(['/photos/editOne/' . $photo->photo_id]);
					}
					
					if ($a === 'em')			// if action == edit more photos
					{
						return $this->redirect(['/photos/editMore']);
					}
					
				}
			}
		}
		
		$this->redirect(['/photos/index']);
	}
	
	
	public function actionSelectAddTo($a, $id)
	{
	
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		if (Albums::findOne($id) === null && Groups::findOne($id) === null)		// if id parameter is wrong redirect to index page
		{
			return $this->redirect(['/photos/index']);
		}
	
		if (count(Yii::$app->request->post()) !== 0)		// if there are selected photo with post request
		{
			foreach (Photos::findByUserId(Yii::$app->user->identity->user_id) as $photo)		// get all logged in user's photos
			{
				// in check box name is not allowed . character =>
				// that is why . character must replace with _ character
				if (Yii::$app->request->post(str_replace('.', '_', $photo->photo_path)))
				{
	
					if ($a === 'ata')			// if action == add to album
					{
						if (count(AlbumsPhotos::findByAlbumIdAndPhotoId($id, $photo->photo_id)) === 0)	// if this photos isn't exists yet in the album
						{
							$albumsPhoto = new AlbumsPhotos();
							$albumsPhoto->album_id = $id;
							$albumsPhoto->photo_id = $photo->photo_id;
							$albumsPhoto->save();
						}
					}
					
					if ($a === 'atg')
					{
						if (count(GroupsPhotos::findByGroupIdAndPhotoId($id, $photo->photo_id)) === 0	// if this photos isn't exists yet in the group
							&& $photo->photo_visibility !== 'private')									// and photos's visibility is not private
						{
							$groupPhoto = new GroupsPhotos();
							$groupPhoto->group_id = $id;
							$groupPhoto->photo_id = $photo->photo_id;
							$groupPhoto->save();
						}
					}
					
				}
			}
		}
	
		if ($a === 'ata')		// if action == add to album
		{
			return $this->redirect(['albums/view/' . $id]);		// redirect to viewAlbum page by id
		}
		
		if ($a === 'atg')		// if action == add to group
		{
			return $this->redirect(['groups/view/' . $id]);		// redirect to viewGroup page by id
		}
		
		$this->redirect(['/photos/index']);
	}
	
	
	public function actionEditOnePhoto($id)
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$photo = Photos::findOne($id);
		$model = new EditPhotoForm();
		
		if ($photo === null || $photo->user_id !== Yii::$app->user->identity->user_id 		// if id is wrong or photos not belong to logged in user
			|| ($model->load(Yii::$app->request->post()) && $model->editOnePhoto($photo)))	// or edit photo was successful
		{
			return $this->redirect(['/photos/index']);																// redirect to index page
		}
		
		return $this->render('editPhoto', [
				'model' => $model,
				'photo' => $photo,
		]);
	}
	
	
	// Important!!!!!     <------under development
	public function actionEditMorePhoto()
	{
		if (Yii::$app->user->isGuest)
		{
			return $this->redirect(['/user/login']);
		}
		
		$photo = new Photos();
		$model = new EditPhotoForm();
		
		if ($model->load(Yii::$app->request->post()) && $model->editMorePhoto())
		{
			return $this->redirect(['/photos/index']);
		}
		
		return $this->render('editPhoto', [
				'model' => $model,
				'photo' => $photo,
		]);
	}
	
}
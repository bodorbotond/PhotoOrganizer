<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\tables\Photos;
use app\models\photos\PhotoUploadForm;

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
								'showByExtension', 'showBySize', 'showByVisibility', 'showByUploadDate'
						],
						'rules' => [									// access rules
								[
									'allow' 	=> true,						// allow
									'actions'	=> [							// these actions
													'index',
													'showByExtension', 'showBySize', 'showByVisibility', 'showByUploadDate'
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
								'showBySize'		 => ['get'],
								'showByVisibility' 	=> ['get'],
								'showByUploadDate' 	=> ['get'],
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
		$model = new PhotoUploadForm();
		
		if ($model->load(Yii::$app->request->post()))					// if post request is arrived
		{
			$model->photos = UploadedFile::getInstances($model, 'photos');	// get instance of uploaded photos
			if ($model->upload())												// insert photos to database
			{
				return $this->redirect(['/photos/index']);
			}
		}
		
		return $this->render('index', [
				'model'				=> $model,
				'userPhotos'		=> $userPhotos,
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
	
	
	// working with selected pictures
	
	
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
					
					if ($a === 'd')				// if action == delete
					{
						$photo->delete();				// delete from database
						unlink($photo->photo_path);		// delete from server
					}

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
					
				}
			}
		}
		
		$this->redirect(['/photos/index']);
	}
	
}
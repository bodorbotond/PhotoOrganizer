<?php

namespace app\models\photos;

use Yii;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\base\Model;
use app\models\tables\Photos;

class PhotoUploadForm extends Model
{
	public $photos;

	public function rules()
	{
		return [
				// photos must be file, required, allowed extension, maximum number of uploaded files
				[['photos'],
						'file',
						'skipOnEmpty'	=> false,
						'extensions' 	=> 'png, jpg',
						'maxFiles' 		=> 20,
				],
		];
	}
	
	public function upload()
	{
		if ($this->validate())				// if uploaded files are validate
		{
			
			$path = 'uploads/' . Yii::$app->user->identity->user_id . '/' . Yii::$app->user->identity->user_id;		// path to user's directory where the photos have been saved
			
			foreach ($this->photos as $photo)
			{
				$photoToDB = new Photos();		// photo record which is inserted to database
				$imageDetails = getimagesize($photo->tempName);
				//$today = getdate();
			
				$photoToDB->user_id 		  = Yii::$app->user->identity->user_id;
				$photoToDB->photo_path 		  = $path . '/' . $photo->baseName . '.' . $photo->extension;
				$photoToDB->photo_extension   = $photo->extension;
				$photoToDB->photo_size 		  = $photo->size;
				$photoToDB->photo_height 	  = $imageDetails[1];
				$photoToDB->photo_width 	  = $imageDetails[0];
				$photoToDB->photo_visibility  = 'private';
				$photoToDB->photo_upload_date = date(Yii::$app->params['dateFormat']);
				
				if (!$photoToDB->save())	// if photo record failed to insert database
				{
					return false;				// return false
				}
				else						// else (photo's data insert successfuly into database)
				{
					$photo->saveAs($path . '/' . $photo->baseName . '.' . $photo->extension);		// save photos in user's directory on server
				}
			}
		}
		
		return true;
	}
}
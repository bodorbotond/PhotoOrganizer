<?php

namespace app\models\albums;

use Yii;
use yii\base\Model;
use app\models\tables\Albums;

class CreateAlbumForm extends Model
{
	
	public $albumName;
    public $albumVisibility;

    public function rules()
    {
        return [
            // albumName, albumVisibility are required
            [['albumName', 'albumVisibility'], 'required'],
        	// albumName is validated by validateAlbumName()
        	[['albumName'], 'validateAlbumName'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'albumName' 		=> 'Album Name',
    			'albumVisibility'	=> 'Album Visibility',
    	];
    }
    
    public function validateAlbumName($attribute, $params)
    {    	
    	if (strlen($this->albumName) > 20)
    	{
    		$this->addError($attribute, 'The length of Album Name must be between 0 and 20 character!');
    	}
    	
    	if (count(Albums::findByUserIdAndAlbumName(Yii::$app->user->identity->user_id, $this->albumName)) !== 0)
    	{
    		$this->addError($attribute, 'You have already an album with this album name!');
    	}
    }
    
    public function create()
    {
    	if ($this->validate())
    	{
    		$album = new Albums();
    		
    		$album->user_id 			= Yii::$app->user->identity->user_id;
    		$album->album_name 			= $this->albumName;
    		$album->album_visibility 	= $this->albumVisibility;
    		$album->album_create_date 	= date(Yii::$app->params['dateFormat']);
    		
    		if ($album->save())
    		{
    			return true;
    		}
    	}
    	
    	return false;
    }
    
}

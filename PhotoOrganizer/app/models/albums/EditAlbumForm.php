<?php

namespace app\models\albums;

use Yii;
use yii\base\Model;
use app\models\tables\Albums;

class EditAlbumForm extends Model
{
	public $album;
	
	public $albumName;
    public $albumVisibility;
    public $albumProfilePicturePath;
    
    public function __construct($album)
    {
    	$this->album = $album;
    }

    public function rules()
    {
        return [
            // albumName, albumVisibility, albumProfilePicturePath are required
            [['albumName', 'albumVisibility', 'albumProfilePicturePath'], 'required'],
        	// albumName is validated by validateAlbumName()
        	[['albumName'], 'validateAlbumName'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'albumName' 		=> 'Album Name',
    			'albumVisibility'	=> 'Album Visibility',
    			'albumProfilePicturePath' 	=> 'Album Profile Picture',
    	];
    }
    
    public function validateAlbumName($attribute, $params)
    {    	
    	if (strlen($this->albumName) > 20)
    	{
    		$this->addError($attribute, 'The length of Album Name must be between 0 and 20 character!');
    	}
    	
    	if (count(Albums::findByUserIdAndAlbumName(Yii::$app->user->identity->user_id, $this->albumName)) !== 0		// if logged in user has already an album with this name
    		&& $this->album->album_name !== $this->albumName)														// except recently edited album
    	{
    		$this->addError($attribute, 'You have already an album with this album name!');
    	}
    }
    
    public function edit()
    {
    	if ($this->validate())
    	{
    		$this->album->album_name 					= $this->albumName;
    		$this->album->album_visibility 				= $this->albumVisibility;
    		$this->album->album_profile_picture_path 	= $this->albumProfilePicturePath;
    
    		if ($this->album->update())
    		{
    			return true;
    		}
    	}
    	 
    	return false;
    }
    
}

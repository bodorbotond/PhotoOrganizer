<?php

namespace app\models\photos;

use Yii;
use yii\base\Model;
use app\models\tables\Photos;
use app\utility\SessionManager;

class EditPhotoForm extends Model
{
	public $title;
	public $tag;
	public $description;
	public $visibility;
	
	public function rules()
	{
		return [
				// title, tag, description, visibility, are required
				[['title', 'tag', 'description', 'visibility'], 'required'],
				// title is validated by validateLength()
				['title', 'validateLength'],
				// tag is validated by validateLength()
				['tag', 'validateLength'],
				// description is validated by validateDescription()
				['description', 'validateDescription'],
		];
	}
	
	public function attributeLabels()						// name of attributes in the browser
	{
		return [
				'title'			=> 'Title',
				'tag'			=> 'Tag',
				'description' 	=> 'Description',
				'visibility'	=> 'Visibility',
		];
	}
	
	public function validateLength($attribute, $params)
	{
		if (strlen($this->title) > 25 || strlen($this->tag) > 25)
		{
			$this->addError($attribute, 'The length of this attribute must be between 0 and 25 character!');
		}
	}
	
	public function validateDescription($attribute, $params)
	{
		if (strlen($this->description) > 200)
		{
			$this->addError($attribute, 'The length of Description must be between 0 and 200 character!');
		}
	}
	
	public function editOnePhoto($photo)
	{
		if ($this->validate())
		{
			$photo->photo_title 		= $this->title;
			$photo->photo_tag  			= $this->tag;
			$photo->photo_description 	= $this->description;
			$photo->photo_visibility 	= $this->visibility;
			
			if($photo->update())
			{
				return true;
			}
		}
		return false;
	}
	
	public function editMorePhoto($photoIds)
	{
		if ($this->validate())
		{
			foreach ($photoIds as $id)
			{
				$photo = Photos::findOne($id);
				
				$photo->photo_title 		= $this->title;
				$photo->photo_tag  			= $this->tag;
				$photo->photo_description 	= $this->description;
				$photo->photo_visibility 	= $this->visibility;
				
				if (!$photo->update())
				{
					return false;
				}
			}
		}
		return true;
	}

}

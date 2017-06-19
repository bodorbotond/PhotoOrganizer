<?php

namespace app\models\groups;

use Yii;
use yii\base\Model;
use app\models\tables\Groups;

class EditGroupForm extends Model
{
	public $group;
	
	public $groupName;	
	public $groupVisibility;
	public $groupProfilePicturePath;
	
	public function __construct($group)
	{
		$this->group = $group;
	}

    public function rules()
    {
        return [
            // groupName, groupVisibility, groupProfilePicturePath are required
            [['groupName', 'groupVisibility', 'groupProfilePicturePath'], 'required'],
        	// groupName is validated by validateAlbumName()
        	[['groupName'], 'validateGroupName'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'groupName' 				=> 'Group Name',
    			'groupVisibility'			=> 'Group Visibility',
    			'groupProfilePicturePath' 	=> 'Group Profile Picture'
    	];
    }
    
    public function validateGroupName($attribute, $params)
    {    	
    	if (strlen($this->groupName) > 20)
    	{
    		$this->addError($attribute, 'The length of Group Name must be between 0 and 20 character!');
    	}
    	
    	// if exists a group with this group name except the recently edited group
    	if (count(Groups::findByGroupName($this->groupName)) !== 0 && $this->group->group_name !== $this->groupName)
    	{
    		$this->addError($attribute, 'Already exists a group with this group name!');
    	}
    }
    
    public function edit()
    {
    	if ($this->validate())
    	{
    		$this->group->group_name 					= $this->groupName;
    		$this->group->group_visibility 				= $this->groupVisibility;
    		$this->group->group_profile_picture_path 	= $this->groupProfilePicturePath;
    		
    		if ($this->group->update())
    		{
    			return true;
    		}
    	}
    	
    	return false;
    }
    
}

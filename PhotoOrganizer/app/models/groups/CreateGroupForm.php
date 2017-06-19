<?php

namespace app\models\groups;

use Yii;
use yii\base\Model;
use app\models\tables\Groups;
use app\models\tables\GroupsUsers;
use app\models\tables\app\models\tables;

class CreateGroupForm extends Model
{
	public $groupName;	
	public $groupVisibility;
	public $groupProfilePicturePath;

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
    	
    	if (count(Groups::findByGroupName($this->groupName)) !== 0)
    	{
    		$this->addError($attribute, 'Already exists a group with this group name!');
    	}
    }
    
    public function create()
    {
    	if ($this->validate())
    	{
    		$group = new Groups();			// create new Group
    		
    		$group->user_id 					= Yii::$app->user->identity->user_id;
    		$group->group_name 					= $this->groupName;
    		$group->group_visibility 			= $this->groupVisibility;
    		$group->group_create_date 			= date(Yii::$app->params['dateFormat']);
    		$group->group_profile_picture_path 	= $this->groupProfilePicturePath;
    		
    		if ($group->save())				// if group insert to database successfuly
    		{
    			$groupUser = new GroupsUsers();		// create new group user
    			
    			$groupUser->group_id = $group->group_id;
    			$groupUser->user_id = $group->user_id;
    			
    			if($groupUser->save())			// if group user insert to database successfuly
    			{
    				return true;
    			}
    		}
    	}
    	
    	return false;
    }
    
}

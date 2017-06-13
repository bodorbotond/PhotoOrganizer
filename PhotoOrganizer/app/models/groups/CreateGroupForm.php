<?php

namespace app\models\groups;

use Yii;
use yii\base\Model;
use app\models\tables\Groups;

class CreateGroupForm extends Model
{
	public $groupName;	
	public $groupVisibility;

    public function rules()
    {
        return [
            // groupName, groupVisibility are required
            [['groupName', 'groupVisibility'], 'required'],
        	// groupName is validated by validateAlbumName()
        	[['groupName'], 'validateGroupName'],
        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'groupName' 		=> 'Group Name',
    			'groupVisibility'	=> 'Group Visibility',
    	];
    }
    
    public function validateGroupName($attribute, $params)
    {    	
    	if (strlen($this->groupName) > 20)
    	{
    		$this->addError($attribute, 'The length of Group Name must be between 0 and 20 character!');
    	}
    	
    	if (count(Groups::findByUserIdAndGroupName(Yii::$app->user->identity->user_id, $this->groupName)) !== 0)
    	{
    		$this->addError($attribute, 'You have already an group with this group name!');
    	}
    }
    
    public function create()
    {
    	if ($this->validate())
    	{
    		$group = new Groups();
    		
    		$group->user_id 			= Yii::$app->user->identity->user_id;
    		$group->group_name 			= $this->groupName;
    		$group->group_visibility 	= $this->groupVisibility;
    		$group->group_create_date 	= date(Yii::$app->params['dateFormat']);
    		
    		if ($group->save())
    		{
    			return true;
    		}
    	}
    	
    	return false;
    }
    
}

<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use app\models\Users;

class SearchUserForm extends Model
{
	
	public $searchText;

    public function rules()
    {
        return [
            // searchText is required
            [['searchText'], 'required'],
        	// searchText is validated by validateSearchText()
        	[['searchText'], 'validateSearchText'],        		
        ];
    }
    
    public function attributeLabels()						// name of attributes in the browser
    {
    	return [
    			'searchText' => 'Search Text',
    	];
    }
    
    public function validateSearchText($attribute, $params)
    {
    	if (strlen($this->searchText) < 1 || strlen($this->searchText) > 50)
    	{
    		$this->addError($attribute, 'The length of search text must be between 1 and 50 character!');
    	}
    }
    
    public function searchUser()
    {
    	$query = 'SELECT * FROM `Users` where `user_name` LIKE \'%' . $this->searchText . '%\'';
    	return Users::findBySql($query)->all();
    }
    
}

<?php

namespace app\models\tables;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "groups".
 *
 * @property integer $group_id
 * @property integer $user_id
 * @property string $group_name
 * @property string $group_visibility
 * @property string $group_create_date
 * @property string $group_profile_picture_path
 *
 * @property Users $user
 * @property GroupsPhotos[] $groupsPhotos
 * @property GroupsUsers[] $groupsUsers
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'group_name', 'group_visibility', 'group_create_date', 'group_profile_picture_path'], 'required'],
            [['user_id'], 'integer'],
            [['group_name'], 'string', 'max' => 20],
            [['group_visibility', 'group_create_date'], 'string', 'max' => 10],
            [['group_profile_picture_path'], 'string', 'max' => 200],
            [['group_name'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
            'group_name' => 'Group Name',
            'group_visibility' => 'Group Visibility',
            'group_create_date' => 'Group Create Date',
            'group_profile_picture_path' => 'Group Profile Picture Path',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsPhotos()
    {
        return $this->hasMany(GroupsPhotos::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsUsers()
    {
        return $this->hasMany(GroupsUsers::className(), ['group_id' => 'group_id']);
    }
    
    public static function findByUserId($id)
    {
    	return self::find()
    					->where(['user_id' => $id])
    					->all();
    }
    
    public static function findByGroupName($groupName)		// for validate group name (CreateGroupForm.php) at create group
    {
    	return self::find()
				    	->where(['group_name' => $groupName])
				    	->all();
    }
}

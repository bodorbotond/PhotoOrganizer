<?php

namespace app\models\tables;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "groups_users".
 *
 * @property integer $groups_users_id
 * @property integer $group_id
 * @property integer $user_id
 *
 * @property Groups $group
 * @property Users $user
 */
class GroupsUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'required'],
            [['group_id', 'user_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'group_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'groups_users_id' => 'Groups Users ID',
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
    
    public static function findByGroupId($id)
    {
    	return self::find()
    					->where(['group_id' => $id])
    					->all();
    }
    
    public static function findByUserId($id)
    {
    	return self::find()
    				->where(['user_id' => $id])
    				->all();
    }
    
    public static function findByGroupIdAndUserId($groupId, $userId)			// check whether a user is a member in a group
    {
    	return self::find()
    	->where(['group_id' => $groupId, 'user_id' => $userId])
    	->all();
    }
}

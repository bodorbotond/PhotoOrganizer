<?php

namespace app\models\tables;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "group_notifications".
 *
 * @property integer $groups_notification_id
 * @property integer $group_id
 * @property integer $user_id
 * @property string $notification_text
 *
 * @property Groups $group
 * @property Users $user
 */
class GroupNotifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id', 'notification_text'], 'required'],
            [['group_id', 'user_id'], 'integer'],
            [['notification_text'], 'string', 'max' => 200],
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
            'groups_notification_id' => 'Groups Notification ID',
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
            'notification_text' => 'Notification Text',
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
    
    public static function findByGroupIdAndUserId($groupId, $userId)		// check has notification by group and user id
    {																		// (use at viewGroup and join to group)
    	return self::find()
    					->where(['group_id' => $groupId, 'user_id' => $userId])
    					->all();
    }
    
    public static function findByGroupId($id)
    {
    	return self::find()
    					->where(['group_id' => $id])
    					->all();
    }
}

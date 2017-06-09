<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "old_passwords".
 *
 * @property integer $old_password_id
 * @property integer $user_id
 * @property string $old_password
 *
 * @property Users $user
 */
class OldPasswords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'old_passwords';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'old_password'], 'required'],
            [['user_id'], 'integer'],
            [['old_password'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'old_password_id' => 'Old Password ID',
            'user_id' => 'User ID',
            'old_password' => 'Old Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
    
    public static function findByUserId($id)			// find all old passwords by username
    {    	
    	return self::find()
    					->where(['user_id' => $id])
    					->all();
    }
    
    public function validatePassword($password)
    {
    	return hash_equals($this->old_password, crypt($password, '_J9..rasm'));
    }

}

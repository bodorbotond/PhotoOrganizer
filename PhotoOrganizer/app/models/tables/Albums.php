<?php

namespace app\models\tables;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "albums".
 *
 * @property integer $album_id
 * @property integer $user_id
 * @property string $album_name
 * @property string $album_visibility
 * @property string $album_create_date
 *
 * @property Users $user
 */
class Albums extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'albums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'album_name', 'album_visibility', 'album_create_date'], 'required'],
            [['user_id'], 'integer'],
            [['album_name'], 'string', 'max' => 20],
            [['album_visibility', 'album_create_date'], 'string', 'max' => 10],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'album_id' => 'Album ID',
            'user_id' => 'User ID',
            'album_name' => 'Album Name',
            'album_visibility' => 'Album Visibility',
            'album_create_date' => 'Album Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
    
    public static function findByUserIdAndAlbumName($id, $albumName)
    {
    	return self::find()
    					->where(['user_id' => $id, 'album_name' => $albumName])
    					->all();
    }
}
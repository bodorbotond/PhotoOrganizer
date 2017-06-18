<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "albums_photos".
 *
 * @property integer $albums_photos_id
 * @property integer $album_id
 * @property integer $photo_id
 *
 * @property Albums $album
 * @property Photos $photo
 */
class AlbumsPhotos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'albums_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_id', 'photo_id'], 'required'],
            [['album_id', 'photo_id'], 'integer'],
            [['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => Albums::className(), 'targetAttribute' => ['album_id' => 'album_id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'photo_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'albums_photos_id' => 'Albums Photos ID',
            'album_id' => 'Album ID',
            'photo_id' => 'Photo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(Albums::className(), ['album_id' => 'album_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['photo_id' => 'photo_id']);
    }
    
    public static function findByAlbumIdAndPhotoId($albumId, $photoId)		// check whether already exists a photo in an album by ids
    {
    	return self::find()
    					->where(['album_id' => $albumId, 'photo_id' => $photoId])
    					->all();
    }
}

<?php

namespace app\models\tables;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "photos".
 *
 * @property integer $photo_id
 * @property integer $user_id
 * @property string $photo_path
 * @property string $photo_extension
 * @property integer $photo_size
 * @property integer $photo_height
 * @property integer $photo_width
 * @property string $photo_title
 * @property string $photo_tag
 * @property string $photo_description
 * @property string $photo_visibility
 * @property string $photo_upload_date
 *
 * @property Users $user
 */
class Photos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'photo_path', 'photo_extension', 'photo_size', 'photo_height', 'photo_width', 'photo_upload_date'], 'required'],
            [['user_id', 'photo_size', 'photo_height', 'photo_width'], 'integer'],
            [['photo_path', 'photo_description'], 'string', 'max' => 200],
            [['photo_extension', 'photo_visibility', 'photo_upload_date'], 'string', 'max' => 10],
            [['photo_title', 'photo_tag'], 'string', 'max' => 25],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'photo_id' => 'Photo ID',
            'user_id' => 'User ID',
            'photo_path' => 'Photo Path',
            'photo_extension' => 'Photo Extension',
            'photo_size' => 'Photo Size',
            'photo_height' => 'Photo Height',
            'photo_width' => 'Photo Width',
            'photo_title' => 'Photo Title',
            'photo_tag' => 'Photo Tag',
            'photo_description' => 'Photo Description',
            'photo_visibility' => 'Photo Visibility',
            'photo_upload_date' => 'Photo Upload Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
    
    public static function findByUserId($id)
    {
    	return self::find()
    					->where(['user_id' => $id])
    					->all();
    }
    
    public static function findByExtension($extension)
    {
    	return self::find()
    					->where(['photo_extension' => $extension, 'user_id' => Yii::$app->user->identity->user_id])
    					->all();
    }
    
    public static function findOrderBy($orderBy)
    {
    	return self::find()
    					->where(['user_id' => Yii::$app->user->identity->user_id])
    					->orderBy($orderBy)
    					->all();
    }
    
    public static function findByVisibility($visibility)
    {
    	return self::find()
				    	->where(['photo_visibility' => $visibility, 'user_id' => Yii::$app->user->identity->user_id])
				    	->all();
    }
    
    public static function findBetweenTwoDate($dateFrom, $dateTo)
    {
    	return self::find()
    					->where(['between', 'photo_upload_date', $dateFrom, $dateTo])
    					->andWhere(['user_id' => Yii::$app->user->identity->user_id])
    					->all();
    }
}

<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "groups_photos".
 *
 * @property integer $groups_photos_id
 * @property integer $group_id
 * @property integer $photo_id
 *
 * @property Groups $group
 * @property Photos $photo
 */
class GroupsPhotos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'photo_id'], 'required'],
            [['group_id', 'photo_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'group_id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'photo_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'groups_photos_id' => 'Groups Photos ID',
            'group_id' => 'Group ID',
            'photo_id' => 'Photo ID',
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
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['photo_id' => 'photo_id']);
    }
}
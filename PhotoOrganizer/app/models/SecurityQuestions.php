<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "security_questions".
 *
 * @property integer $question_id
 * @property string $question_text
 *
 * @property UsersSequrityQuestions[] $usersSequrityQuestions
 */
class SecurityQuestions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'security_questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_text'], 'required'],
            [['question_text'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question ID',
            'question_text' => 'Question Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSequrityQuestions()
    {
        return $this->hasMany(UsersSequrityQuestions::className(), ['question_id' => 'question_id']);
    }
}

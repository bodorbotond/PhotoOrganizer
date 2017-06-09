<?php

namespace app\models\tables;

use Yii;
use yii\db\Query;
use app\models\Users;

/**
 * This is the model class for table "users_sequrity_questions".
 *
 * @property integer $u_s_q_id
 * @property integer $user_id
 * @property integer $question_id
 * @property string $answer
 *
 * @property SecurityQuestions $question
 * @property Users $user
 */
class UsersSequrityQuestions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_sequrity_questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'question_id', 'answer'], 'required'],
            [['user_id', 'question_id'], 'integer'],
            [['answer'], 'string', 'max' => 200],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => SecurityQuestions::className(), 'targetAttribute' => ['question_id' => 'question_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'u_s_q_id' => 'U S Q ID',
            'user_id' => 'User ID',
            'question_id' => 'Question ID',
            'answer' => 'Answer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(SecurityQuestions::className(), ['question_id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }
    
    public static function getUserSecurityQuestions()
    {
    	return self::find()
						->where(['user_id' => Yii::$app->user->identity->user_id])
						->all();
    }
    
    public static function findByUserId($id)
    {
    	return self::find()									// get user's security answers by user id
    				->where(['user_id' => $id])
    				->all();
    }
    
    public static function getUserSecurityQuestionsByUserId($id)
    {  	
    	$query = new Query();
    	 
    	$query->select ('sq.question_text')					// get user's security questions by user id
    		  ->from ('security_questions sq, users_sequrity_questions usq')
    		  ->where ('sq.question_id = usq.question_id and usq.user_id = ' . $id);
    	 
    	return $query->all();
    }
    
}

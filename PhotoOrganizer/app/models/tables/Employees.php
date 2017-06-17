<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property integer $employee_id
 * @property string $user_name
 * @property string $first_name
 * @property string $last_name
 * @property string $e_mail
 * @property string $password
 * @property string $profile_picture_path
 */
class Employees extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'first_name', 'last_name', 'e_mail'], 'required'],
            [['user_name', 'first_name', 'last_name', 'e_mail', 'password'], 'string', 'max' => 50],
            [['profile_picture_path'], 'string', 'max' => 200],
            [['user_name'], 'unique'],
            [['e_mail'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'user_name' => 'User Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'e_mail' => 'E Mail',
            'password' => 'Password',
            'profile_picture_path' => 'Profile Picture Path',
        ];
    }
}

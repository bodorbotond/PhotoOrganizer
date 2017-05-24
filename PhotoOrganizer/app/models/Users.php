<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $first_name
 * @property string $last_name
 * @property string $e_mail
 * @property string $recovery_e_mail
 * @property string $password
 * @property string $gender
 * @property string $profile_picture_path
 * @property string $auth_key
 * @property string $account_status
 * @property string $verification_key
 * @property integer $two_step_verification
 *
 * @property Photos[] $photos
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'first_name', 'last_name', 'e_mail', 'password', 'gender', 'auth_key', 'account_status', 'verification_key'], 'required'],
            [['two_step_verification'], 'integer'],
            [['user_name', 'first_name', 'last_name', 'e_mail', 'recovery_e_mail', 'password'], 'string', 'max' => 50],
            [['gender', 'account_status'], 'string', 'max' => 10],
            [['profile_picture_path'], 'string', 'max' => 200],
            [['auth_key'], 'string', 'max' => 30],
            [['verification_key'], 'string', 'max' => 6],
            [['user_name'], 'unique'],
            [['e_mail'], 'unique'],
            [['recovery_e_mail'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'e_mail' => 'E Mail',
            'recovery_e_mail' => 'Recovery E Mail',
            'password' => 'Password',
            'gender' => 'Gender',
            'profile_picture_path' => 'Profile Picture Path',		// save just the photo's access path in the database
            'auth_key' => 'Auth Key',								// enable auto login when the session is destroyed
            'account_status' => 'Account Status',					// wheter the user activated the account(default inactive)
            'verification_key' => 'Verification Key',				// activation key what the server will send to the users in email
            'two_step_verification' => 'Two Step Verification',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['user_id' => 'user_id']);
    }
    
    public function getAuthKey()
    {
    	return $this->auth_key;
    }
    
    public function getId()
    {
    	return $this->user_id;
    }
    
    public function validateAuthKey($authKey)
    {
    	return $this->auth_key === $authKey;
    }
    
    public static function findIdentity($id)
    {
    	return self::findOne($id);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	throw new NotSupportedException();
    }
    
    public static function findByUsername($userName)					// wheter exists user_name like parameter($userName) in the database
    {
    	return self::findOne(['user_name' => $userName]);
    }
    
    public function validatePassword($password)
    {
    	return hash_equals($this->password, crypt($password, 'salt'));
    }
    
    public static function findByEMail($eMail)
    {
    	return self::findOne(['e_mail' => $eMail]);
    }
    
    public function validateAccountStatus()								// check user activate her status
    {
    	return $this->account_status === 'active';
    }
    
    public static function findByVerificationKey($verificationKey)		// find user whom user_token equal to given parameter
    {
    	return self::findOne(['verification_key' => $verificationKey]);
    }
}

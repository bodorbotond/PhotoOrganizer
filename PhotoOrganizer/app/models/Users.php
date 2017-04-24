<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $e_mail
 * @property string $password
 * @property string $profile_picture_path
 * @property string $auth_key
 * @property string $user_status
 * @property string $user_token
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
            [['user_name', 'e_mail', 'password', 'auth_key', 'user_status', 'user_token'], 'required'],
            [['user_name', 'e_mail', 'password'], 'string', 'max' => 50],
            [['profile_picture_path'], 'string', 'max' => 200],
            [['auth_key', 'user_status'], 'string', 'max' => 30],
            [['user_token'], 'string', 'max' => 6],
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
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'e_mail' => 'E Mail',
            'password' => 'Password',
            'profile_picture_path' => 'Profile Picture Path',		// save just the photo's access path in the database
            'auth_key' => 'Auth Key',								// enable auto login when the session is destroyed
            'user_status' => 'User Status',							// wheter the user activated the account(default inactive)
            'user_token' => 'User Token',							// activation key what the server will send to the users in email
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
    
    public function validateUserStatus()								// check user activate her status
    {
    	 return $this->user_status === 'active';
    }
    
    public static function findByUserToken($userToken)					// find user whom user_token equal to given parameter
    {
    	return self::findOne(['user_token' => $userToken]);
    }
}

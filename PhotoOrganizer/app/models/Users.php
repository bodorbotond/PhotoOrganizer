<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $first_name
 * @property string $last_name
 * @property string $e_mail
 * @property string $e_mail_visibility
 * @property string $recovery_e_mail
 * @property string $password
 * @property string $gender
 * @property string $profile_picture_path
 * @property string $auth_key
 * @property string $account_status
 * @property string $verification_key
 * @property integer $two_step_verification
 *
 * @property Albums[] $albums
 * @property Groups[] $groups
 * @property GroupsUsers[] $groupsUsers
 * @property OldPasswords[] $oldPasswords
 * @property Photos[] $photos
 * @property UsersSequrityQuestions[] $usersSequrityQuestions
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
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
            [['user_name', 'first_name', 'last_name', 'e_mail', 'e_mail_visibility', 'password', 'gender', 'profile_picture_path', 'auth_key', 'account_status', 'verification_key'], 'required'],
            [['two_step_verification'], 'integer'],
            [['user_name', 'first_name', 'last_name', 'e_mail', 'recovery_e_mail', 'password'], 'string', 'max' => 50],
            [['e_mail_visibility', 'gender', 'account_status'], 'string', 'max' => 10],
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
            'e_mail_visibility' => 'E Mail Visibility',
            'recovery_e_mail' => 'Recovery E Mail',
            'password' => 'Password',
            'gender' => 'Gender',
            'profile_picture_path' => 'Profile Picture Path',
            'auth_key' => 'Auth Key',
            'account_status' => 'Account Status',
            'verification_key' => 'Verification Key',
            'two_step_verification' => 'Two Step Verification',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Albums::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupsUsers()
    {
        return $this->hasMany(GroupsUsers::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOldPasswords()
    {
        return $this->hasMany(OldPasswords::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSequrityQuestions()
    {
        return $this->hasMany(UsersSequrityQuestions::className(), ['user_id' => 'user_id']);
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
    	return hash_equals($this->password, crypt($password, '_J9..rasm'));
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

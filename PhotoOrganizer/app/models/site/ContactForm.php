<?php

namespace app\models\site;

use Yii;
use yii\base\Model;
use app\utility\email\UserContactSendEmail;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $eMail;
    public $subject;
    public $body;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'eMail', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['eMail', 'email'],
        ];
    }
    
    public function attributeLabels()
    {
    	return [
    			'name' 		=> 'Name',
    			'eMail' 	=> 'E-mail',
    			'subject'	=> 'Subject',
    			'body' 		=> 'Body',
    	];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact()
    {
        if ($this->validate())
        {
        	return UserContactSendEmail::sendEMail($this->name, $this->eMail, $this->subject, $this->body);
        }
        
        return false;
    }
}

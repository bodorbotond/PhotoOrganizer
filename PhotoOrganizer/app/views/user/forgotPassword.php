<?php

use yii\helpers\Html;
use yii\web\Session;
use yii\bootstrap\Alert;

$this->title = 'Forgot Password';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/user/login']];
$this->params['breadcrumbs'][] = ['label' => 'User Identify', 'url' => ['/user/userIdentify']];
$this->params['breadcrumbs'][] = $this->title;

$session = new Session();
$session->open();

if (isset($session['errorMessage']))				// error messages (arrived when user want to choose
{													// password remembering option what he/she can't use)
	echo Alert::widget([									// alert error message
			'options' => [
					'class' => 'alert-info',
			],
			'body' => $session['errorMessage'],
	]);
}

unset($session['errorMessage']);					// delete session variable
$session->close();

?>

<div class="site-forgot-password">

	<br>	
	<div class="row">	
		<h4>You have three different options to remembering your password:</h4>	
	</div>
	<br><br>
	
 	<div class="col-md-4 text-center">
 		
 		<br>
 		You can ask from us to send a verification key to your e-mail adress or recovery e-mail adress.
 		<br>
 		You don't have to provide a recovery email address, but having one makes it easier for you to regain 
 		access to your account beacuse we can send you an email with a verification key. After you entered this 
 		key correctly you have permission to change your password.
 		<br><br>
 		<?= Html::a("E-mail" , ['/user/forgotPassword/sendToEmailAdress'], ['class' => 'btn btn-default'])?>
 		&nbsp;&nbsp;
 		<?= Html::a("Recovery E-mail" , ['/user/forgotPassword/sendToRecoveryEmailAdress'], ['class' => 'btn btn-default'])?>
 		
 	</div>
 	
  	<div class="col-md-4 text-center">
  	
  		<br>
  		If your account is protected with security questions, we can provide you change password functionality, 
  		after you correctly answer your questions.
  		<br><br><br><br><br><br><br>
  		<?= Html::a("Security Question" , ['/user/forgotPassword/securityQuestions'], ['class' => 'btn btn-default'])?>
  	
  	</div>
  	
  	<div class="col-md-4 text-center">
  	
  		<br>
  		If you changed password ever your old password was saved. It can help you to regain 
 		access to your account with change password functionality if you remember at least one of your old passwords.
  		<br><br><br><br><br><br>
  		<?= Html::a("Old Passwords" , ['/user/forgotPassword/oldPasswords'], ['class' => 'btn btn-default'])?>
  	
  	</div>
  	
</div>
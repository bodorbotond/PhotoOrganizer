<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

$this->title = 'Account Info';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/account.css');

// profle picture path(two way: - logged in user has choosen optionally own profile picture
// 							    - logged in user has default profile picture from server

$profilePicturePath = Yii::$app->user->identity->profile_picture_path !== NULL ?
					  '@web/' . Yii::$app->user->identity->profile_picture_path :
					  '@web/images/profile_picture.png';

// loged in user's personal info (first element from tabs menu)

$personalInfo = '<br>'
				. 'Manage this basic information - your name, email, profile picture - to help others find you on Groups'
				. '<br><br>'
				// profile picture in larger size (in Bootstrap Modal)
				. Html::a(Html::img($profilePicturePath, ['class' => 'img-circle', 'id' => 'ProfilePicture']), ['#ProfilePictureModal'], ['data-toggle' => 'modal']) 
				. '<br><br>'
				. Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4>User Name</h4>',
				            'content' 	=> '<b>' . Yii::$app->user->identity->user_name . '</b>',
				        ],
				        [
				            'label' 	=> '<h4>Name</h4>',
				            'content' 	=> 'First Name: <b>' . Yii::$app->user->identity->first_name . '</b>' . 
				            			   '<br>Last Name: <b>' . Yii::$app->user->identity->last_name . '</b>',
				        ],
			    		[
				    		'label' 	=> '<h4>E-mail</h4>',
				    		'content' 	=> '<b>' . Yii::$app->user->identity->e_mail . '</b>',
			    		],
				    	[
				    		'label' 	=> '<h4>Gender</h4>',
				    		'content' 	=> '<b>' . Yii::$app->user->identity->gender . '</b>',
				    	],
				    ]
				])
				. '<br><br>'
				. Html::a('Modify Personal Info', ['/account/perosnalInfo/modify'], ['class' => 'btn btn-default']);



// loged in user's account security (second element from tabs menu)

$accountSecurity = 	'<br>'
					. Collapse::widget([
						'encodeLabels'	=> false,
						'items'			=> [
								[
									'label' 	=> '<h4>Change Password</h4>',
									'content' 	=> 'Your password protects your account.<br>
													Choose a strong password and don\'t reuse it for other accounts. '
													. Html::a('Learn More', [''])
													. '<br><br>'
													. Html::a('Change Password', ['/account/security/changePassword'], ['class' => 'btn btn-default']),
								],
								[
									'label'		=> '<h4>Recovery E-mail Address</h4>',
									'content' 	=> 'If you forget your password or cannot access your account, 
													we will use this information to help you get back in.<br><br>'
													.
													(
														Yii::$app->user->identity->recovery_e_mail !== NULL															// if user already has a recovery e-mail adress
														?
														'<b>' . Yii::$app->user->identity->recovery_e_mail . '</b>'													// then display recovery e-mail adress
														. '<br><br>' 
														. Html::a('Modify Recovery E-mail', ['/account/security/recoveryEmail/addOrModify'], ['class' => 'btn btn-default'])	// Modify
														. '&nbsp&nbsp'
														. Html::a('Delete Recovery E-mail', ['/account/security/recoveryEmail/delete'], ['class' => 'btn btn-default'])		// and Delete Recovery Email button
														:
														Html::a('Add Recovery E-mail', ['/account/security/recoveryEmail/addOrModify'], ['class' => 'btn btn-default'])		// else display Add Recovery Email button
													),
								],
								[
									'label'		=> '<h4>Two Step Verification</h4>',
									'content' 	=> 'Each time you sign in to your account, you\'ll need your 
													password and a verification code.
													<br> 
													You can add a second layer of protection with 2-Step Verification, 
													which sends a single-use code to your e-mail for you to enter when you sign in. 
													So even if somebody manages to steal your password, it is not enough to get into 
													your account.
													<br><br>'
													. Html::beginForm(['/account/security/twoStepVerification'], 'post')									// on/off two step verification form with one checkbox
													. '<b>Two Step Verification</b>&nbsp&nbsp'
													. Html::checkbox('TwoStepVerificationCheckBox',
																	 Yii::$app->user->identity->two_step_verification === 1 ? true : false,		// if user's two step verification is active checkbox is checked
																	 ['onchange' => 'this.form.submit()'])		// submit without submit button
													. Html::endForm()
													. (Yii::$app->user->identity->two_step_verification === 1 ?
													'<br>Your account is protected with two step verification.' :								// and print this information 
													''),
								],
								[
									'label'		=> '<h4>Security Questions</h4>',
									'content'	=> 'Security questions help in verify that you\'re the person requesting access to 
													your account.'
													. (count($usersSecurityQuestionsAndAnswers) === 0
													?
													'<br><br>You don\'t have any sequrity questions.<br><br>'
													. Html::a('Add Security Questions', ['/account/security/securityQuestions/add'], ['class' => 'btn btn-default'])
													:
													'<br><br>Your security questions:<br><br>'
													. $usersSecurityQuestionsAndAnswers[0]['question_text']
													. '<br>&emsp;&emsp;<b>' . $usersSecurityQuestionsAndAnswers[0]['answer'] . '</b><br><br>'
													. $usersSecurityQuestionsAndAnswers[1]['question_text']
													. '<br>&emsp;&emsp;<b>' . $usersSecurityQuestionsAndAnswers[1]['answer'] . '</b><br>'
													. '<br><br>'
													. Html::a('Modify Security Questions', ['/account/security/securityQuestions/modify'], ['class' => 'btn btn-default'])
													. '&nbsp&nbsp'
													. Html::a('Delete Security Questions', ['/account/security/securityQuestions/delete'], ['class' => 'btn btn-default'])),	
								],
						]
					]);

?>
	
	
<div class="site-account-info">

	<h1><?= Html::encode($this->title) ?></h1>
		
	<?php
	echo Tabs::widget([										//Bootstrap Toggleable/Dynamic Tabs
		'encodeLabels' 	=> false,
		'items' 		=> [
	        [
	            'label' 	=> 'Personal Info',
	            'content' 	=> $personalInfo,
	            'active' 	=> true,
	        	'options'	=> ['class' => 'fade in']
	        ],
	        [
	            'label' 	=> 'Account Security',
	            'content' 	=> $accountSecurity,
	            'options' 	=> ['class' => 'fade']
	        ],
	    ],
	]);
	
	?>

	
	<!-- Bootstrap Modal For Profile Picture-->
	
	<div id="ProfilePictureModal" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
	
	    <!-- Modal content-->
	    <div class="modal-content">
	    
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Profile Picture</h4>
	      </div>
	      
	      <div class="modal-body">
	        <?php
	        echo Html::img($profilePicturePath, ['id' => 'ProfilePictureInModal'])
	        . '<br>'
	        . (Yii::$app->user->identity->profile_picture_path !== NULL ?											// if user has profile picture
	          Html::a('Delete', ['/account/perosnalInfo/deleteProfilePicture'], ['class' => 'btn btn-danger']) :	// then use can delete it
	          '');																									// else Delete button is not required
	        ?>
	      </div>
	      
	    </div>
	
	  </div>
	</div>
	
	
</div>
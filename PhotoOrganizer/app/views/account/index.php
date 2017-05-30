<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;

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
				. Html::a('Modify', ['/account/modifyPersonalInfo'], ['class' => 'btn btn-default']);



// loged in user's account security (second element from tabs menu)

$accountSecurity = 	'<br>'
					. Collapse::widget([
						'encodeLabels'	=> false,
						'items'			=> [
								[
									'label' 	=> '<h4>Change Password</h4>',
									'content' 	=> '',
								],
								[
									'label'		=> '<h4>Recovery E-mail Address</h4>',
									'content' 	=> 'If you forget your password or cannot access your account, 
													we will use this information to help you get back in.<br><br>'
													.
													(
														Yii::$app->user->identity->recovery_e_mail !== NULL
														?
														'<b>' . Yii::$app->user->identity->recovery_e_mail . '</b>'
														. '<br><br>' 
														. Html::a('Modify Recovery E-mail', ['/account/addOrModifyRecoveryEmail'], ['class' => 'btn btn-default'])
														. '&nbsp&nbsp'
														. Html::a('Delete Recovery E-mail', ['/account/deleteRecoveryEmail'], ['class' => 'btn btn-default'])
														:
														Html::a('Add Recovery E-mail', ['/account/addOrModifyRecoveryEmail'], ['class' => 'btn btn-default'])
													),
								],
								[
									'label'		=> '<h4>Two Step Verification</h4>',
									'content' 	=> '',
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

	
	<!-- Bootstrap Modal -->
	
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
	        . (Yii::$app->user->identity->profile_picture_path !== NULL ?								// if user has profile picture
	          Html::a('Delete', ['/account/deleteProfilePicture'], ['class' => 'btn btn-danger']) :		// then use can delete it
	          '');																						// else Delete button is not required
	        ?>
	      </div>
	      
	    </div>
	
	  </div>
	</div>
	
	
	
</div>
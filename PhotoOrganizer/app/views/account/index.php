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
				            'content' 	=> Yii::$app->user->identity->user_name,
				        ],
				        [
				            'label' 	=> '<h4>Name</h4>',
				            'content' 	=> 'First Name: ' . Yii::$app->user->identity->first_name .
				            			   '<br>Last Name: ' . Yii::$app->user->identity->last_name
				        ],
			    		[
				    		'label' 	=> '<h4>E-mail</h4>',
				    		'content' 	=> Yii::$app->user->identity->e_mail,
			    		],
				    	[
				    		'label' 	=> '<h4>Gender</h4>',
				    		'content' 	=> Yii::$app->user->identity->gender,
				    	],
				    ]
				])
				. '<br><br>'
				. Html::a('Modify', ['/account/modifyPersonalInfo'], ['class' => 'btn btn-default']);

// loged in user's account security (second element from tabs menu)

$accountSecurity = '';

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
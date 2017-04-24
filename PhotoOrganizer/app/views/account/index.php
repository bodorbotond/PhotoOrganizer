<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;

$this->title = 'Account Info';
$this->params['breadcrumbs'][] = $this->title;
;;
$this->registerJsFile('@web/js/account.js');

$personalInfo = '<br>'
				. Html::img('@web/' . Yii::$app->user->identity->profile_picture_path, ['class' => 'img-circle', 'width' => '300', 'height' => '300']) 
				. '<br><br>'
				. Collapse::widget([
				    'items' => [
				        [
				            'label' 	=> 'User Name',
				            'content' 	=> /*'<span id="userName">' 
				        					. Yii::$app->user->identity->user_name
				        					. '</span>'
				        					. Html::a('Save', ['#'], [
				        												'id' => 'userNameSaveButton', 'style' => 'visibility: hidden;',
				        												'class' => 'btn btn-default btn-sm pull-right'
				        					 						  ]
				        					),*/
							        		Html::beginForm(['/account/changeAccountPersonalInfo'], 'post')
							        				. Html::textInput('changeUserName', Yii::$app->user->identity->user_name, ['style' => 'border: none;'])
							        				. '&nbsp&nbsp'
							        				. Html::submitButton('Change', ['class' => 'btn btn-default btn-sm pull-right'])
							        		. Html::endForm(),
				        	'contentOptions' => ['onClick' => 'setEditable("userName", "userNameSaveButton");'],
				        ],
				        [
				            'label' 	=> 'Name',
				            'content' 	=> [
				            	'First Name: ' . Yii::$app->user->identity->user_name . Html::a('Change', ['#'], ['class' => 'btn btn-default btn-xs pull-right']),
				            	'Last Name: ' . Yii::$app->user->identity->user_name . Html::a('Change', ['#'], ['class' => 'btn btn-default btn-xs pull-right']),
				            ],
				        ],
				    	[
				    		'label' 	=> 'Gender',
				    		'content' 	=> 'Male' . Html::a('Change', ['#'], ['class' => 'btn btn-default btn-sm pull-right']),
				    	],
				    	[
				    		'label' 	=> 'Birthday',
				    		'content' 	=> '1995.12.09' . Html::a('Change', ['#'], ['class' => 'btn btn-default btn-sm pull-right']),
				    	],
				    ]
				]);

$accountSecurity = '';

?>

<div class="site-account-info">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php
	/*Modal::begin([
			'header' => '<h2>Change User Name</h2>',
			'toggleButton' => ['label' => 'Change', 'class' => 'btn btn-default btn-sm pull-right'],
	]);
	
	echo Html::beginForm(['/account/changeAccountPersonalInfo'], 'post')
			. Html::label('User Name:', 'changeUserName')
			. '&nbsp&nbsp'
			. Html::textInput('changeUserName')
			. '&nbsp&nbsp'
			. Html::submitButton('Change', ['class' => 'btn btn-default'])
		. Html::endForm();
	
	Modal::end();*/
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
	        [
	            'label' 	=> 'Dropdown',
	        	'options' 	=> ['class' => 'fade'],
	            'items' 	=> [
	                 [
	                     'label' 	=> 'DropdownA',
	                     'content' 	=> '',
	                 ],
	                 [
	                     'label' 	=> 'DropdownB',
	                     'content' 	=> '',
	                 ],
	                 [
	                     'label' 	=> 'External Link',
	                 	 'content' 	=> '',
	                     'url' 		=> '',
	                 ],
	            ],
	        ],
			[
				'label'		=>	'<i class="glyphicon glyphicon-king"></i> Disabled',
				'headerOptions' => ['class' => 'disabled']
			],
	    ],
	]);
	?>
	
</div>
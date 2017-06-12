<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
use yii\bootstrap\Dropdown;

$this->title = 'My Photos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-my-photos">

    <h1><?= Html::encode($this->title) ?></h1>

    <br><br>
		
	<?php if(count($userPhotos) === 0) : ?>									<!-- if user has not any photos,
																				 show only upload form -->	
																				 
		<div class="text-center">
			
			<h3>Got a lot of photos? We have got a lot of space for you.</h3>
			
    		<?php $form = ActiveForm::begin([
		        'id' => 'photo-upload-form',
		        'options' => ['enctype' => 'multipart/form-data'],
		    ]); ?>
		    
		    	<br>
		    	<label for="UploadPhotoButton" id="LabelForUploadPhotoButton" class="btn btn-default">
    				<span class="glyphicon glyphicon-cloud"></span>
    				&nbsp;&nbsp;
    				Choose Files
				</label>
        
        		<?= $form->field($model, 'photos[]')->fileInput([
        												'id' => 'UploadPhotoButton',
	            										'multiple' => true,
	            										'accept' => 'image/*',
	            										'onchange'	=> 'this.form.submit();',				            		
 													  ])->label(false); ?>
 													  
    		<?php ActiveForm::end(); ?>
    		
    	</div>
		
	<?php else : ?>															<!-- else (user has uploaded photos)
																				 show photos and user's options -->
												<!-- menu -->
		<div id="PhotosMenu">
		
			<div class="dropdown inline">
			   	<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Show By <b class="caret"></b></a>
			    <?php
			        echo Dropdown::widget([
			            'items' => [
			                ['label' => 'Extension', 'url' => ['/photos/show/extension']],
			                ['label' => 'Size', 'url' => ['/photos/show/size']],
			            	['label' => 'Visibility', 'url' => ['/photos/show/visibility']],
			            	['label' => 'Upload Date', 'url' => ['/photos/show/uploadDate']],
			            ],
			        ]);
			    ?>
			</div>
			
			<div class="dropdown inline">
			   	<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Select <b class="caret"></b></a>
			    <?php
			        echo Dropdown::widget([
			            'items' => [
			                [
			                	'label' 	=> '<div id="SelectButton">Select</div>',
			                	'encode' 	=> false,
			                	'options' 	=> ['onclick' => 'changeSelectVisibility()']
			        		],
			                [
			                	'label' 	=> '<div id="SelectAllButton">Select All</div>',
			                	'encode' 	=> false,
			                	'options' 	=> ['onclick' => 'changeAllSelectVisibility()']
			        		],
			            ],
			        ]);
			    ?>			    
			</div>
			
			<div class="dropdown inline">
			   	<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Organize <b class="caret"></b></a>
			    <?php
			        echo Dropdown::widget([
			            'items' => [
			                ['label' => 'Extension', 'url' => ['/photos/show/extension']],
			                ['label' => 'Size', 'url' => ['/photos/show/size']],
			            	['label' => 'Visibility', 'url' => ['/photos/show/visibility']],
			            	['label' => 'Upload Date', 'url' => ['/photos/show/uploadDate']],
			            ],
			        ]);
			    ?>
			</div>
			
			<div class="btn btn-default" onclick="submitSelectButton()">Delete</div>
			
		</div>
		
		<br><br>
			
		<div class="text-center">				<!-- user's photos -->
			
			<div class="well">
				
				<?php
				echo Html::beginForm(['/photos/select'], 'post', ['id' => 'SelectForm']);		// select form
				
				foreach ($userPhotos as $photo):												// loop in user's photos
				?>
				
					<div class="userPhoto">
					
						<?= Html::img('@web/' . $photo->photo_path); ?>											<!-- user's photo -->
						
						<?= Html::checkbox($photo->photo_path, false, ['class' => 'imageSelectCheckBox']); ?>	<!-- select checkbox (checkbox's name = photo access path on the server,
																												but checkbox's name is not allowed . character, it is replaced with _ character) -->
						<div>
							<?= explode('/', $photo->photo_path)[sizeof(explode('/', $photo->photo_path)) - 1]; // photo name ?>
						</div>
						
    				</div>
    				
    			<?php
    			endforeach;
    			
    			echo Html::endForm();
    			?>
				
				<br class="clearBoth" />
				
			</div>
		
			<br><br>							<!-- uploads more photo form-->
			
			<?php
			$form = ActiveForm::begin([
				        'id' => 'photo-upload-form',
				        'options' => ['enctype' => 'multipart/form-data'],
				   ]);

			 echo Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Upload More Photos</h4>',
				            'content' 	=> '<h3>Got a lot of photos? We have got a lot of space for you.</h3><br>' 
				        					. '<label for="UploadPhotoButton" id="LabelForUploadPhotoButton" class="btn btn-default">
    												<span class="glyphicon glyphicon-cloud"></span>&nbsp;&nbsp;Choose Files
											  </label>'
				        					. $form->field($model, 'photos[]')->fileInput([
				            														'id' => 'UploadPhotoButton',
				        															'multiple' 	=> true,
				            														'accept' 	=> 'image/*',
				            														'onchange'	=> 'this.form.submit();',				            		
			 																     ])->label(false),
			 			],	
					]
    			]);
			 

			ActiveForm::end();
			?>

		</div>

	<?php endif; ?>
	
</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Album';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = ['label' => $album->album_name, 'url' => ['/albums/view/' . $album->album_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="edit-create-album">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to edit album:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'edit-album-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
    	<?= $form->field($model, 'albumName')->textInput(['value' => $album->album_name]); ?>
    	
    	<?= $form->field($model, 'albumVisibility')->dropDownList([
    																'private'	=> 'private',
    																'public'	=> 'public',
    															   ],
    															   ['options' => [$album->album_visibility => ['selected' => true]]]
    	); ?> 
    	
    	<?= $form->field($model, 'albumProfilePicturePath')->dropDownList([
			    															'images/album_profile_picture1.jpg'	=> 'Picture1',
			    															'images/album_profile_picture2.jpg'	=> 'Picture2',
			    															'images/album_profile_picture3.jpg'	=> 'Picture3',
															    		   ],
															    		   ['options' => [$album->album_profile_picture_path => ['selected' => true]]]
    	); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Edit Album', ['class' => 'btn btn-primary', 'name' => 'edit-album-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    
    <br><br>
    
    <div id="UserPhotos">				
			<div class="well">
			    	
				    <div class="userPhoto">							
						<?= Html::img('@web/images/album_profile_picture1.jpg'); ?>						
						<div>
							Picture1
						</div>								
		    		</div>
		    		
		    		<div class="userPhoto">							
						<?= Html::img('@web/images/album_profile_picture2.jpg'); ?>						
						<div>
							Picture2
						</div>								
		    		</div>
		    		
		    		<div class="userPhoto">							
						<?= Html::img('@web/images/album_profile_picture3.jpg'); ?>						
						<div>
							Picture3
						</div>								
		    		</div>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>	    	
	    </div>

</div>

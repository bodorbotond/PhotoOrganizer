<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create Group';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['/groups/index']];
$this->params['breadcrumbs'][] = ['label' => $group->group_name, 'url' => ['/groups/view/' . $group->group_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-edit-group">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to edit group:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'edit-group-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
 		<?= $form->field($model, 'groupName')->textInput(['value' => $group->group_name]); ?>
    	
    	<?= $form->field($model, 'groupVisibility')->dropDownList([
    																'private'	=> 'private',
    																'public'	=> 'public',
    															  ],
    															  ['options' => [$group->group_visibility => ['selected' => true]]]
    	); ?>
    	
    	<?= $form->field($model, 'groupProfilePicturePath')->dropDownList([
		    																'images/group_profile_picture1.png'	=> 'Picture1',
		    																'images/group_profile_picture2.png'	=> 'Picture2',
		    																'images/group_profile_picture3.png'	=> 'Picture3',
														    			  ],
														    			  ['options' => [$group->group_profile_picture_path => ['selected' => true]]]
    	); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Edit Group', ['class' => 'btn btn-primary', 'name' => 'edit-group-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    
    <br><br>
    
    <div id="UserPhotos">				
			<div class="well">
			    	
				    <div class="userPhoto">							
						<?= Html::img('@web/images/group_profile_picture1.png'); ?>						
						<div>
							Picture1
						</div>								
		    		</div>
		    		
		    		<div class="userPhoto">							
						<?= Html::img('@web/images/group_profile_picture2.png'); ?>						
						<div>
							Picture2
						</div>								
		    		</div>
		    		
		    		<div class="userPhoto">							
						<?= Html::img('@web/images/group_profile_picture3.png'); ?>						
						<div>
							Picture3
						</div>								
		    		</div>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>	    	
	    </div>    

</div>

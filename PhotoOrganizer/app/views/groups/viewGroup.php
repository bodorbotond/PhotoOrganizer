<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Collapse;
use yii\bootstrap\Dropdown;

$this->title = $group->group_name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['/groups/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-group">

    	<h1><?= Html::encode($this->title) ?></h1>
    
    	<br>
    
    	<div>										<!-- album's propertys -->
    		<b>Number of photos: <?= count($groupPhotos); ?></b>
    		<br>
    		<b>Number of users: <?= count($groupUsers); ?></b>
    		<br>
    		<b>Administrator: <?= $administrator->user_name; ?></b>
    		<br>
			<b><?= ucfirst($group->group_visibility); ?></b>
    	</div>
    	
    	<br><br>   	
    	
		<div id="GroupMenu">						<!-- menu -->
    	
    		<?= Html::a('Add Users', ['/groups/index/'], ['class' => 'btn btn-default']) ?>
    		
    		<?= Html::a('Add Photos', ['/photos/index/'], ['class' => 'btn btn-default']) ?>
    		
    		<br><br>
		
			<div class="dropdown inline">
			   	<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Select <b class="caret"></b></a>
			    <?php
			        echo Dropdown::widget([
			            'items' => [
			                [
			                	'label' 	=> '<div id="SelectButton" class="dropDownButton">Select</div>',
			                	'encode' 	=> false,
			                	'options' 	=> ['onclick' => 'setCheckBoxesVisible()']
			        		],
			                [
			                	'label' 	=> '<div id="SelectAllButton" class="dropDownButton">Select All</div>',
			                	'encode' 	=> false,
			                	'options' 	=> ['onclick' => 'setAllCheckBoxesVisibleAndChecked()']
			        		],
			            	[
			            		'label' 	=> '<div id="ClearSelectionButton" class="dropDownButton">Clear Selection</div>',
			            		'encode' 	=> false,
			            		'options' 	=> ['onclick' => 'clearSelection()']
			            	],
			            ],
			        ]);
			    ?>			    
			</div>
			
			<div id="RemoveButton" class="btn btn-default" onclick="removePhotosFromGroup('<?= Url::home('http'); ?>', '<?= $group->group_id; ?>')">Remove From Group</div>
			
			<?= Html::a('Edit Group', ['/groups/edit/' . $group->group_id], ['class' => 'btn btn-default']) ?>
			
			<?= Html::a('Delete Group', ['/groups/delete/' . $group->group_id], ['class' => 'btn btn-default', 'onclick' => 'return confirm(\'Are you sure about delete this group?\')']) ?>
			
		</div>
		
		<!-- photos in group -->
		
		<br><br>
    	
    	<h3>Photos</h3>
    	
	    <div id="UserPhotos">
				
			<div class="well">
			
				<?php 
				echo Html::beginForm([''], 'post', ['id' => 'SelectForm']);
	    
			    foreach($groupPhotos as $photo):
			    ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $photo['photo_path'], [''])); ?>
						
						<?= Html::checkbox($photo['photo_path'], false, ['class' => 'imageSelectCheckBox']); ?>											<!-- user's photo -->
								
						<div>
							<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; ?>
						</div>
								
		    		</div>
		    		
		    	<?php
		    	echo Html::endForm();

		    	endforeach;
		    	?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
	    
	    <!-- users in group -->
    	
    	<br><br>
    	
    	<h3>Users</h3>
    	
	    <div id="UserPhotos">
				
			<div class="well">
	    
			    <?php foreach($groupUsers as $user): ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $user['profile_picture_path'], [''])); ?>											<!-- user's photo -->
								
						<div>
							<?= $user['user_name']; ?>
						</div>
								
		    		</div>
		    		
		    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
    	</div>
    
</div>
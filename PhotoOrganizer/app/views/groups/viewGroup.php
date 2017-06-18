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
    
    <br><br>
    
     <?php if(count($groupUsers) === 0):?>		<!-- if group not contain any users -->
    
    	<div class="text-center">
    	
    		<h3>Nobody belongs to this group except you. Add some users to share photo between each other.</h3>    		
    		<br>    
    		<?= Html::a('Add Users', ['/groups/index'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if album contain photos) -->
    
	    <div class="text-center">					<!-- add more users -->
	    
			<?= Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Add More Users</h4>',
				            'content' 	=> '<h3>You want more users in this group? Add some.</h3>    		
    										<br>'
				        					. Html::a('Add Users', ['/groups/index'], ['class' => 'btn btn-default btn-larger']),
				        ],	
					]
    		]); ?>
    	
    	</div>
    	
    	
    	<div id="PhotosMenu">						<!-- menu -->
		
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
    	
    	
	    <div id="UserPhotos">						<!-- user's album's photos -->
				
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
	    
	<?php endif; ?>
    
</div>
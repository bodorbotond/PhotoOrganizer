<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Collapse;
use yii\bootstrap\Dropdown;

$this->title = $group->group_name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['/groups/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-group-for-administrator">

    	<h1><?= Html::encode($this->title) ?></h1>
    
    	<br>
    
    	<div class="row">											<!-- group's profile picture -->
	    	
	    	<div class="col-md-4">
	    		<?= Html::a(Html::img('@web/' . $group->group_profile_picture_path,
	    					 ['id' => 'ProfilePicture', 'class' => 'img-circle']),
	    			['#ProfilePictureModal'], 
	    			['data-toggle' => 'modal']); ?>
	    	</div>
	    	
	    	<br><br>
	    	
	    	<div class="col-md-4">										<!-- group's propertys -->
	    		<b class="propertys"><span class="yellow">Administrator: </span><?= $administrator->user_name; ?></b>
	    		<br>
	    		<b class="propertys"><span class="yellow">Number of photos: </span><?= $photosNumber; ?></b>
	    		<br>
	    		<b class="propertys"><span class="yellow">Number of users: </span><?= $usersNumber; ?></b>
	    		<br>
				<b class="propertys"><?= ucfirst($group->group_visibility); ?> Group</b>
	    	</div>
	    	
	    	<div class="col-md-4">
	    	
	    		<h4>Users With Join Intension</h4>
	    	
	    		<div id="UserPhotos">
				
					<div class="well">
	    	
	    				<?php foreach ($usersWithJoinIntension as $user): ?>
	    		
	    					<div class="userPhoto">
								
								<?= Html::a(Html::img('@web/' . $user['profile_picture_path']), ['/search/users/view/' . $user['user_id']]); ?>											<!-- user's photo -->
										
								<div>
									<?= $user['user_name']; ?>
								</div>
									
			    			</div>
	    		
	    				<?php endforeach; ?>
	    				
	    				<br class="clearBoth">
	    				
	    			</div>
	    			
	    		</div>
	    	
	    	</div>
	    	
    	</div>
    	
    	
    	<br>
    	
    	<div id="GroupMenu">						<!-- menu -->
	    	
	    		<?= Html::a('Add Users', ['/search/searchUser'], ['class' => 'btn btn-default']) ?>
	    		
	    		<?= Html::a('Add Photos', ['/photos/index'], ['class' => 'btn btn-default']) ?>
	    		
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
				
				<div id="RemoveButton" class="btn btn-default" style="display:none;" onclick="removeFromGroup('<?= Url::home('http'); ?>', '<?= $group->group_id; ?>')">Remove From Group</div>
				
				<?= Html::a('Edit Group', ['/groups/edit/' . $group->group_id], ['class' => 'btn btn-default']) ?>
				
				<?= Html::a('Delete Group', ['/groups/delete/' . $group->group_id], ['class' => 'btn btn-default', 'onclick' => 'return confirm(\'Are you sure about delete this group?\')']) ?>
				
		</div>
		
		
		<!-- photos -->
		
		
		<br><br>
    	
	    <div id="UserPhotos" onclick="setRemoveButtonVisibility()">
				
			<div class="well">
			
				<?= Html::beginForm([''], 'post', ['id' => 'SelectForm']); ?>			<!-- select form -->
				
					<h3>Photos</h3>
					
					<?php foreach ($groupPublicPhotos as $photo): ?>					<!-- private photos -->
					
						<div class="userPhoto" >
						
							<?= Html::a(Html::img('@web/' . $photo['photo_path']),		// one photo
										['#PhotosModal'], 
	    								['data-toggle' => 'modal',
	    								 'onclick' => "setModalBody('" . Url::home('http') . "', '" . $photo['photo_path'] . "', '" . $photo['photo_tag'] . "', '" . $photo['photo_title'] . "', '" . $photo['photo_description'] . "')"]); ?>
							
							<?= Html::checkbox($photo['photo_path'], false, ['class' => 'imageSelectCheckBox']); ?>	<!-- select checkbox (checkbox's name = photo access path on the server,
																													but checkbox's name is not allowed . character, it is replaced with _ character) -->
							<div>
								<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; // photo name ?>
								<br>
								<?= $photo['photo_visibility']; ?>
								<br>
								<?= $photo['user_name']; ?>
							</div>
							
	    				</div>
	    				
	    			<?php endforeach; ?>
	    			
	    			<br class="clearBoth">
    			
    			
			</div>		<!-- well class -->
			
		</div>		<!-- UserPhotos class -->
		
		
		<!-- users -->

    	
    	<br><br>
    	
	    <div id="UserPhotos" onclick="setRemoveButtonVisibility()">
				
			<div class="well">
				
					<h3>Users</h3>
	    
				    <?php foreach($groupUsers as $user): ?>
				    	
					    <div class="userPhoto">
								
							<?= Html::a(Html::img('@web/' . $user['profile_picture_path']), ['/search/users/view/' . $user['user_id']]); ?>											<!-- user's photo -->
							
							<?= Html::checkbox($user['user_id'], false, ['class' => 'imageSelectCheckBox']); ?>
									
							<div>
								<?= $user['user_name']; ?>
								<?= intval($user['user_id']) === $administrator->user_id ? '<br>Administrator' : ''; ?>
							</div>
									
			    		</div>
			    		
			    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth">
		    	
		    	<?= Html::endForm(); ?>
	    
	    	</div>		<!-- well class -->
			
		</div>		<!-- UserPhotos class -->
		
		
		
		<!-- Bootstrap Modal For Album Profile Picture-->
	
		<div id="ProfilePictureModal" class="modal fade" role="dialog">
		  	<div class="modal-dialog modal-lg">
		
			    <div class="modal-content">
			    
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Group Profile Picture</h4>
			      </div>
			      
			      <div class="modal-body">
			        <?= Html::img('@web/' . $group->group_profile_picture_path, ['id' => 'ProfilePictureInModal']);	?>
			      </div>
			      
			    </div>
		
		  	</div>
		</div>
		
		
		
		<!-- Bootstrap Modal For Photos-->
	
		<div id="PhotosModal" class="modal fade" role="dialog">
		  	<div class="modal-dialog modal-lg">
		
			    <div class="modal-content">
			    
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
			      
			      <div id="PhotoModalBody" class="modal-body">
			      	<!-- here will be set the cliked photo from javascript with setModalBody() function -->
			      </div>
			      
			      <div id="PhotoModalFooter" class="modal-footer">
			      </div>
			      
			    </div>
		
		  	</div>
		</div>
		
		
    
</div>
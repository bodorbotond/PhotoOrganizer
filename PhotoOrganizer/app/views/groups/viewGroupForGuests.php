<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $group->group_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-group-for-guests">

    	<h1><?= Html::encode($this->title) ?></h1>
    
    	<br>   
    	
    	<div class="row">											<!-- album's profile picture -->
	    	
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
	    	
    	</div>
    	
    	<br><br>
    	
    	 <div id="UserPhotos">
				
			<div class="well">
			
				<?= Html::beginForm([''], 'post', ['id' => 'SelectForm']); ?>			<!-- select form -->
				
					<h3>Photos</h3>
					
					<?php foreach ($groupPublicPhotos as $photo): ?>					<!-- private photos -->
					
						<div class="userPhoto">
						
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
    			
	    		<?= Html::endForm(); ?>
				
			</div>		<!-- well class -->
			
		</div>		<!-- UserPhotos class -->
		
		
		<!-- users -->

    	
    	<br><br>
    	
	    <div id="UserPhotos">
				
			<div class="well">
			
				<?= Html::beginForm([''], 'post', ['id' => 'SelectForm']); ?>			<!-- select form -->
				
					<h3>Users</h3>
	    
				    <?php foreach($groupUsers as $user): ?>
				    	
					    <div class="userPhoto">
								
							<?= Html::a(Html::img('@web/' . $user['profile_picture_path']), ['/search/users/view/' . $user['user_id']]); ?>											<!-- user's photo -->
									
							<div>
								<?= $user['user_name']; ?>
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
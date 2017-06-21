<?php

use yii\helpers\Html;

$this->title = $group->group_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-group">

	<h1><?= Html::encode($this->title) ?></h1>
	
	<br>
    
    	<div class="row">											<!-- group's profile picture -->
	    	
	    	<div class="col-md-4">
	    		<?= Html::img('@web/' . $group->group_profile_picture_path, ['id' => 'ProfilePicture', 'class' => 'img-circle']); ?>
	    	</div>
	    	
	    	<br><br>
	    	
	    	<div class="col-md-4">										<!-- group's propertys -->
	    		<b class="propertys"><span class="yellow">Number of photos: </span><?= count($groupPhotos); ?></b>
	    		<br>
	    		<b class="propertys"><span class="yellow">Number of users: </span><?= count($groupUsers); ?></b>
	    		<br>
	    		<b class="propertys"><span class="yellow">Administrator: </span><?= $user->user_name; ?></b>
	    		<br>
				<b class="propertys"><?= ucfirst($group->group_visibility); ?></b>
				
				<br><br>
				<?= Html::a('<span class="glyphicon glyphicon-plus"></span> Join', [''], ['class' => 'btn btn-primary']); ?>
    		
	    	</div>
	    	
    	</div>
    	
    	<?php if($group->group_visibility !== 'private'): ?>
    	
	    	<br><br>
	    	
	    	<h3>Public Photos</h3>
	    	
		    <div id="UserPhotos">
					
				<div class="well">
				
					<?php foreach($groupPhotos as $photo): ?>
					
						<?php if ($photo['photo_visibility'] !== 'private'): ?>		<!-- show just public photos -->
				    	
						    <div class="userPhoto">
									
								<?= Html::a(Html::img('@web/' . $photo['photo_path'], [''])); ?><?= Html::checkbox($photo['photo_path'], false, ['class' => 'imageSelectCheckBox']); ?>											<!-- user's photo -->
										
								<div>
									<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; ?>
								</div>
										
				    		</div>
				    		
				    	<?php endif; ?>
			    		
			    	<?php endforeach; ?>
			    	
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
	    	
	    <?php endif; ?>
	
</div>
<?php

use yii\helpers\Html;

$this->title = $album->album_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-album">

	<h1><?= Html::encode($this->title) ?></h1>
	
	<br>   
    	
    	<div class="row">											<!-- album's profile picture -->
	    	
	    	<div class="col-md-4">
	    		<?= Html::img('@web/' . $album->album_profile_picture_path, ['id' => 'ProfilePicture', 'class' => 'img-circle']); ?>
	    	</div>
	    	
	    	<br><br>
	    	
	    	<div class="col-md-4">										<!-- album's propertys -->
				<b class="propertys"><span class="yellow">Administrator: </span><?= $user->user_name ?></b>
				<br>
	    		<b class="propertys"><span class="yellow">Number of photos: </span><?= count($albumPhotos); ?></b>
	    		<br>
				<b class="propertys"><?= ucfirst($album->album_visibility); ?></b>
    		</div>
    		
    	</div>
    	
    	<?php if($album->album_visibility !== 'private'): ?>
    	
	    	<br><br>
	    	
	    	<h3>Public Photos</h3>
	    	
	    	<div id="UserPhotos"">				<!-- user's album's photos -->
				
				<div class="well">
					
					<?php foreach ($albumPhotos as $photo): ?>
					
						<?php if ($photo['photo_visibility'] !== 'private'): ?>		<!-- show just public photos -->
					
							<div class="userPhoto">
							
								<?= Html::a(Html::img('@web/' . $photo['photo_path']), ['']); ?>											<!-- user's photo -->
								
								<div>
									<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; // photo name ?>
								</div>
								
		    				</div>
		    				
		    			<?php endif; ?>
	    				
	    			<?php endforeach; ?>
					
					<br class="clearBoth" />
					
				</div>
				
			</div>
		
		<?php endif; ?>
	
</div>
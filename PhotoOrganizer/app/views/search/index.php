<?php

use yii\helpers\Html;

$this->title = 'Search Result';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-search-result">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
    <div class="col-md-4 text-center">			<!-- search result for users -->
    
    	<h3>Users</h3>
    	
	    <div id="UserPhotos">
				
			<div class="well">
			
				<?php foreach($users as $user): ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $user['profile_picture_path']), ['/search/users/view/' . $user['user_id']]); ?>
						
						<div>
							<?= $user['user_name']; ?>
						</div>
								
		    		</div>
		    		
		    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
    
    </div>
    
    
    <div class="col-md-4 text-center">			<!-- search result for albums -->
    
    	<h3>Albums</h3>
    	
	    <div id="UserPhotos">
				
			<div class="well">
			
				<?php foreach($albums as $album): ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $album['album_profile_picture_path']), ['/search/albums/view/' . $album['album_id']]); ?>
						
						<div>
							<?= $album['album_name']; ?>
						</div>
								
		    		</div>
		    		
		    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
    
    </div>
    
    
    
    <div class="col-md-4 text-center">			<!-- search result for groups -->
    
    	<h3>Groups</h3>
    	
	    <div id="UserPhotos">
				
			<div class="well">
			
				<?php foreach($groups as $group): ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $group['group_profile_picture_path']), ['/search/groups/view/' . $group['group_id']]); ?>
						
						<div>
							<?= $group['group_name']; ?>
						</div>
								
		    		</div>
		    		
		    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
    
    </div>
    	
    	
    
</div>
<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = 'Albums';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-my-albums">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
    <?php if(count($userAlbums) === 0):?>		<!-- if user has not albums -->
    
    	<div class="text-center">
    	
    		<h3>You have not any albums yet? Create a new one for organize your photo more effective way.</h3>    		
    		<br>    
    		<?= Html::a('Create New Album', ['/albums/create'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if user has albums) -->
    
	   <div class="text-center">
	    	
	    	<?= Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Create More Albums</h4>',
				            'content' 	=> '<h3>You have not any albums yet? Create a new one for organize your photo more effective way.</h3>    		
    										<br>'
				        					. Html::a('Create New Album', ['/albums/create'], ['class' => 'btn btn-default btn-larger']),
				        ],	
					]
    			]); ?>
    	
    	</div>
	   
	    <div id="UserPhotos">						<!-- user's albums -->
				
			<div class="well">
	    
			    <?php foreach($userAlbums as $album): ?>
			    
			    	<?php if ($album->is_empty): ?>				<!-- if album not contain any photos
			    															=> album's cover photo from server -->
				    	<div class="userPhoto">
							
							<?= Html::a(Html::img('@web/images/empty_album.png'), ['/albums/viewAlbum/' . $album->album_id]); ?>											<!-- user's photo -->
								
							<div>
								<?= $album->album_name; ?>
							</div>
								
		    			</div>
		    			
		    		<?php else: ?>								<!-- else (if album contain photos)
			    															=> album's cover photo is photos from album -->		    		
		    			<div class="userPhoto">
							
							<?= Html::img('@web/'); ?>
								
							<div>
								<?= $album->album_name; ?>
							</div>
								
		    			</div>
		    		
		    		<?php endif; ?>
			    
			    <?php endforeach;?>
			    
			    <br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
	    
	   <?php endif; ?>
    
</div>
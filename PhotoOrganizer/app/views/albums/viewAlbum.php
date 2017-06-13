<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = $album->album_name;
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-album">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
     <?php if(count($albumPhotos) === 0):?>		<!-- if album not contain any photos -->
    
    	<div class="text-center">
    	
    		<h3>There is no picture in this album. Add some photos.</h3>    		
    		<br>    
    		<?= Html::a('Add Photos', ['/photos/index'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if album contain photos) -->
    
	    <div id="UserPhotos">						<!-- user's album's photos -->
				
			<div class="well">
	    
			    <?php foreach($albumPhotos as $photo): ?>
			    	
				    <div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $photo['photo_path'], [''])); ?>											<!-- user's photo -->
								
						<div>
							<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; ?>
						</div>
								
		    		</div>
		    		
		    	<?php endforeach; ?>
		    	
		    	<br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
	    
	    <div class="text-center">
	    
	    	<br><br>
	    	
	    	<?= Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Add More Photos</h4>',
				            'content' 	=> '<h3>You want more photos in this album? Add some.</h3>    		
    										<br>'
				        					. Html::a('Add Photos', ['/photos/index'], ['class' => 'btn btn-default btn-larger']),
				        ],	
					]
    			]); ?>
    	
    	</div>
	    
	<?php endif; ?>
    
</div>
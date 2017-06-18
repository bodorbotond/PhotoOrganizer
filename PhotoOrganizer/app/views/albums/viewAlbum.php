<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Collapse;
use yii\bootstrap\Dropdown;

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
    
    	
    	<div class="text-center">					<!-- add more photos to album -->
	    	
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
			
			<div id="RemoveButton" class="btn btn-default" onclick="removePhotosFromAlbum('<?= Url::home('http'); ?>', '<?= $album->album_id; ?>')">Remove From Album</div>
			
			<?= Html::a('Edit Album', ['/albums/edit/' . $album->album_id], ['class' => 'btn btn-default']) ?>
			
			<?= Html::a('Delete Album', ['/albums/delete/' . $album->album_id], ['class' => 'btn btn-default', 'onclick' => 'return confirm(\'Are you sure about delete this album?\')']) ?>
			
		</div>
    	
    	<br><br>
    
	    <br><br>
			
		<div id="UserPhotos" onclick="checkSelection()">				<!-- user's album's photos -->
			
			<div class="well">
				
				<?php
				echo Html::beginForm([''], 'post', ['id' => 'SelectForm']);		// select form
				
				foreach ($albumPhotos as $photo):	// loop in user's photos
				?>
				
					<div class="userPhoto">
					
						<?= Html::a(Html::img('@web/' . $photo['photo_path']), ['']); ?>											<!-- user's photo -->
						
						<?= Html::checkbox($photo['photo_path'], false, ['class' => 'imageSelectCheckBox']); ?>	<!-- select checkbox (checkbox's name = photo access path on the server,
																												but checkbox's name is not allowed . character, it is replaced with _ character) -->
						<div>
							<?= explode('/', $photo['photo_path'])[sizeof(explode('/', $photo['photo_path'])) - 1]; // photo name ?>
						</div>
						
    				</div>
    				
    			<?php
    			endforeach;
    			
    			echo Html::endForm();
    			?>
				
				<br class="clearBoth" />
				
			</div>
			
		</div>
	    
	<?php endif; ?>
    
</div>
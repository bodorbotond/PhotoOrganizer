<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;

$this->title = $album->album_name;
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-album-for-administrator">

    	<h1><?= Html::encode($this->title) ?></h1>
    
    	<br>   
    	
    	<div class="row">											<!-- album's profile picture -->
	    	
	    	<div class="col-md-4">
	    		<?= Html::a(Html::img('@web/' . $album->album_profile_picture_path,
	    					 ['id' => 'ProfilePicture', 'class' => 'img-circle']),
	    			['#ProfilePictureModal'], 
	    			['data-toggle' => 'modal']); ?>
	    	</div>
	    	
	    	<br><br>
	    	
	    	<div class="col-md-4">										<!-- album's propertys -->
	    		<b class="propertys"><span class="yellow">Administrator: </span><?= $administrator->user_name ?></b>
				<br>
	    		<b class="propertys"><span class="yellow">Number of photos: </span><?= $photosNumber; ?></b>
	    		<br>
				<b class="propertys"><?= ucfirst($album->album_visibility); ?> Album</b>
    		</div>
    		
    	</div>
    	
    	<br> 
    	
    	<div id="PhotosMenu">						<!-- menu -->
    	
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
			
			<div id="RemoveButton" class="btn btn-default" onclick="removePhotosFromAlbum('<?= Url::home('http'); ?>', '<?= $album->album_id; ?>')">Remove From Album</div>
			
			<?= Html::a('Edit Album', ['/albums/edit/' . $album->album_id], ['class' => 'btn btn-default']) ?>
			
			<?= Html::a('Delete Album', ['/albums/delete/' . $album->album_id], ['class' => 'btn btn-default', 'onclick' => 'return confirm(\'Are you sure about delete this album?\')']) ?>
			
		</div>
    
	    <br><br>
			
		<div id="UserPhotos" onclick="checkSelection()">				<!-- user's photos in this album -->
			
			<div class="well">
				
				<?= Html::beginForm([''], 'post', ['id' => 'SelectForm']); ?>		<!-- select form -->
				
					<h3>Private Photos</h3>
					
					<?php foreach ($albumPrivatePhotos as $photo): ?>					<!-- private photos -->
					
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
							</div>
							
	    				</div>
	    				
	    			<?php endforeach; ?>
	    			
	    			<br class="clearBoth">
    			
    			
	    			<h3>Public Photos</h3>
					
					<?php foreach ($albumPublicPhotos as $photo): ?>					<!-- public photos -->
					
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
			        <h4 class="modal-title">Album Profile Picture</h4>
			      </div>
			      
			      <div class="modal-body">
			        <?= Html::img('@web/' . $album->album_profile_picture_path, ['id' => 'ProfilePictureInModal']);	?>
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
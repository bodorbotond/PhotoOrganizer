<?php

use yii\helpers\Html;

$this->title = 'Show By ' . $showBy;
$this->params['breadcrumbs'][] = ['label' => 'Photos', 'url' => ['/photos/index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="site-show-by">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
    <?php if($showBy === 'Extension'): ?>				<!-- if show by photo's extension -->

		<h3 class="black">Photos of jpg extension:</h3>
			<h4> <?= count($jpgPhotos); ?> </h4>
		
		<?php foreach($jpgPhotos as $photo): ?>					<!-- jpg photos -->

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= $photo->photo_extension; ?>
				</div>
    		</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>
		
		<h3 class="black">Photos of png extension:</h3>
			<h4> <?= count($pngPhotos); ?> </h4>
		
		<?php foreach($pngPhotos as $photo): ?>					<!-- png photos -->

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= $photo->photo_extension; ?>
				</div>
    		</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>
		
	<?php elseif($showBy === 'Size'): ?>						<!-- if show by photo's size -->
	
		<?php foreach($photos as $photo): ?>

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= (intval($photo->photo_size)/1024 < 1024 					// if photo's size < 1 MB
						? round(intval($photo->photo_size)/1024, 2) . ' KB' 		// display size KB format
						: round(intval($photo->photo_size)/1024/1024, 2) . ' MB')	// display size MB format ?>
    			</div>
			</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>
	
	<?php elseif($showBy === 'Visibility'): ?>				<!-- if show by photo's visibility -->
	
		<h3 class="black">Private photos:</h3>
			<h4> <?= count($privatePhotos); ?> </h4>
		
		<?php foreach($privatePhotos as $photo): ?>				<!-- private photos -->

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= $photo->photo_visibility; ?>
				</div>
    		</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>

		<h3 class="black">Public photos:</h3>
			<h4> <?= count($publicPhotos); ?> </h4>
		
		<?php foreach($publicPhotos as $photo): ?>					<!-- public photos -->

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= $photo->photo_visibility; ?>
				</div>
    		</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>
		
	<?php else: ?>
		
		<h3 class="black">Photos made in 2017:</h3>
			<h4> <?= count($photos2017); ?> </h4>
		
		<?php foreach ($photos2017 as $photo): ?>					<!-- public photos -->

			<div class="userPhoto">
				<?= Html::img('@web/' . $photo->photo_path); ?>
				<div>
					<?= $photo->photo_upload_date; ?>
				</div>
    		</div>
		
		<?php endforeach; ?>
		
		<br class="clearBoth"></br>

	<?php endif; ?>	
    
</div>
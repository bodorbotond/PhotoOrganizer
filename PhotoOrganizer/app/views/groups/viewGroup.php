<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = $group->group_name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['/groups/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-group">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
     <?php if(count($groupUsers) === 0):?>		<!-- if group not contain any users -->
    
    	<div class="text-center">
    	
    		<h3>Nobody belongs to this group except you. Add some users to share photo between each other.</h3>    		
    		<br>    
    		<?= Html::a('Add Users', ['/groups/index'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if album contain photos) -->
    
	    <div id="UserPhotos">						<!-- user's album's photos -->
				
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
	    
	    <div class="text-center">
	    
	    	<br><br>
	    	
	    	<?= Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Add More Users</h4>',
				            'content' 	=> '<h3>You want more users in this group? Add some.</h3>    		
    										<br>'
				        					. Html::a('Add Users', ['/groups/index'], ['class' => 'btn btn-default btn-larger']),
				        ],	
					]
    			]); ?>
    	
    	</div>
	    
	<?php endif; ?>
    
</div>
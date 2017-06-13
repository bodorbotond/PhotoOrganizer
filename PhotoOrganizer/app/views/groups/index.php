<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = 'Groups';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-groups">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
    <?php if(count($userGroups) === 0):?>		<!-- if user has not groups -->
    
    	<div class="text-center">
    	
    		<h3>You have not any groups yet? Create a new one for share photos your friends.</h3>    		
    		<br>    
    		<?= Html::a('Create New Group', ['/groups/create'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if user has groups) -->
    
	    <div id="UserPhotos">						<!-- user's goups -->
				
			<div class="well">
	    
			    <?php foreach($userGroups as $groups): ?>
			    
			    	<?php if ($groups->is_empty): ?>		<!-- if groups not contain any user
			    													=> group's cover photo is from server -->
				    	<div class="userPhoto">
							
							<?= Html::a(Html::img('@web/images/empty_group.jpg'), ['/groups/viewGroup/' . $groups->group_id]); ?>											<!-- user's photo -->
								
							<div>
								<?= $groups->group_name; ?>
							</div>
								
		    			</div>
		    			
		    		<?php else: ?>							<!-- else (if groups contain users)
			    													=> group's cover photo is user's profile picture -->		    		
		    			<div class="userPhoto">
							
							<?= Html::img('@web/'); ?>
								
							<div>
								<?= $groups->group_name; ?>
							</div>
								
		    			</div>
		    		
		    		<?php endif; ?>
			    
			    <?php endforeach;?>
			    
			    <br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
	    
	    <div class="text-center">
	    
	    	<br><br>
	    	
	    	<?= Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4 class="black">Create More Groups</h4>',
				            'content' 	=> '<h3>You have not any groups yet? Create a new one for share photos your friends.</h3>    		
    										<br>'
				        					. Html::a('Create New Group', ['/groups/create'], ['class' => 'btn btn-default btn-larger']),
				        ],	
					]
    			]); ?>
    	
    	</div>
	    
	   <?php endif; ?>
    
</div>

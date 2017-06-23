<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = 'Groups';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-groups">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <br><br>
    
    <?php if(count($userGroups) === 0 && count($otherGroups) === 0):?>		<!-- if user has no groups and not a member in any group -->
    
    	<div class="text-center">
    	
    		<h3>You have not any groups yet? Create a new one for share photos your friends.</h3>    		
    		<br>    
    		<?= Html::a('Create New Group', ['/groups/create'], ['class' => 'btn btn-default btn-larger']) ?>
    		
    		<br><br>
    		
    		<h3>Or you just want to become a member of a group? Search and join one.</h3>    		
    		<br>    
    		<?= Html::a('Join Group', ['/search/searchGroup'], ['class' => 'btn btn-default btn-larger']) ?>
    	
    	</div>
    
    <?php else:?>								<!-- else (if user has groups) -->
    						<!-- user's goups -->
				
		<div class="text-center">

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
    	
    	<!-- logged in user's group -->
    		
    	<br><br>
    	
    	<h3>My groups</h3>
    		
    	<div id="UserPhotos">
			
			<div class="well">
	    
			    <?php foreach($userGroups as $group): ?>
			    
			    	<div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $group->group_profile_picture_path), ['/groups/view/' . $group->group_id]); ?>											<!-- user's photo -->
								
						<div>
							<?= $group->group_name; ?>
						</div>
								
		    		</div>
		    			
		    	<?php endforeach; ?>
			    
			    <br class="clearBoth" />
	    
	    	</div>
	    
	    </div>
	    	
	    <!-- groups where logged in user is a member -->
	    	
	    <br><br>
    	
    	<h3>Groups in where I'm a member</h3>
    	
    	<div id="UserPhotos">
			
			<div class="well">
	    
			    <?php foreach($otherGroups as $group): ?>
			    
			    	<div class="userPhoto">
							
						<?= Html::a(Html::img('@web/' . $group->group_profile_picture_path), ['/groups/view/' . $group->group_id]); ?>											<!-- user's photo -->
								
						<div>
							<?= $group->group_name; ?>
						</div>
								
		    		</div>
		    			
		    	<?php endforeach; ?>
			    
			    <br class="clearBoth" />
	    
	    	</div>
	    	
	    </div>
	    
	   <?php endif; ?>
    
</div>

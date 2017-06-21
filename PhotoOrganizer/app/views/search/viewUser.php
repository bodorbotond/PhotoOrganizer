<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

$this->title = $user->user_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view-user">

	<h1><?= Html::encode($this->title) ?></h1>
	
	<div class="row">											<!-- album's profile picture -->
	    	
	    	<div class="col-md-4">
	    		<?= Html::img('@web/' . $user->profile_picture_path, ['id' => 'ProfilePicture', 'class' => 'img-circle']); ?>
	    	</div>
	    	
	    	<br><br>
	    	
	    	<div class="col-md-4">										<!-- album's propertys -->
				<b class="propertys"><span class="yellow">Username: </span><?= $user->user_name ?></b>
				<br>
				<b class="propertys"><span class="yellow">First Name: </span><?= $user->first_name ?></b>
				<br>
				<b class="propertys"><span class="yellow">Last Name: </span><?= $user->last_name ?></b>
				<?php if($user->e_mail_visibility === 'public'): ?>
					<br>
		    		<b class="propertys"><span class="yellow">E-mail adress: </span><?= $user->e_mail; ?></b>
	    		<?php endif; ?>
	    		<br>
				<b class="propertys"><span class="yellow">Gender: </span><?= $user->gender; ?></b>
				<br>
				<b class="propertys"><span class="yellow">Photos: </span><?= count($userPhotos); ?></b>
				
				<?php if (!Yii::$app->user->isGuest && count($groups) !== 0):?>				<!-- if user is logged in and has at least one group-->
					<br><br>
					
					<div class="text-center">							<!-- show add group option -->
						<?= Collapse::widget([						//Bootstrap Accordion Collapse
							'encodeLabels' => false,
						    'items' => [
						        [
						            'label' 	=> '<h4>Add User To My Group</h4>',
						            'content' 	=> Html::beginForm(['/groups/addUser/' . $user->user_id], 'post')	// show add form
						        						. 'My Groups: '
						        						. Html::dropDownList('GroupId', reset($groups), $groups)
						        						. '<br><br>'
						        						. Html::submitButton('Add', ['class' => 'btn btn-primary'])
												   . Html::endForm(),
						        ],	
							]
		    			]); ?>
					</div>
				<?php endif; ?>
    			
    		</div>
    		
    </div>
    
    <br><br>
	    	
	<h3>Public Photos</h3>
	    	
	<div id="UserPhotos"">				<!-- user's photos -->
				
			<div class="well">
					
				<?php foreach ($userPhotos as $photo): ?>
					
					<?php if ($photo->photo_visibility !== 'private'): ?>	<!-- show just public photos -->
					
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
	
</div>
<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;

$this->title = $employee->user_name;
$this->params['breadcrumbs'][] = ['label' => 'About Photo Organizer', 'url' => ['/site/about']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <br><br>
    
    <?= Html::a(Html::img('@web/' . $employee->profile_picture_path, ['class' => 'img-circle', 'id' => 'ProfilePicture']), ['#ProfilePictureModal'], ['data-toggle' => 'modal']) 
				. '<br><br>'
				. Collapse::widget([						//Bootstrap Accordion Collapse
					'encodeLabels' => false,
				    'items' => [
				        [
				            'label' 	=> '<h4>User Name</h4>',
				            'content' 	=> '<b>' . $employee->user_name . '</b>',
				        ],
				        [
				            'label' 	=> '<h4>Name</h4>',
				            'content' 	=> 'First Name: <b>' . $employee->first_name . '</b>' . 
				            			   '<br>Last Name: <b>' . $employee->last_name . '</b>',
				        ],
			    		[
				    		'label' 	=> '<h4>E-mail</h4>',
				    		'content' 	=> '<b>' . $employee->e_mail . '</b>',
			    		],
				    ]
				]);
	?>
				
				
	<!-- Bootstrap Modal For Profile Picture-->
	
	<div id="ProfilePictureModal" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
	
	    <!-- Modal content-->
	    <div class="modal-content">
	    
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Employee's Profile Picture</h4>
	      </div>
	      
	      <div class="modal-body">
	        <?= Html::img('@web/' . $employee->profile_picture_path, ['id' => 'ProfilePictureInModal']) ?>
	      </div>
	      
	    </div>
	
	  </div>
	</div>
	
    
</div>
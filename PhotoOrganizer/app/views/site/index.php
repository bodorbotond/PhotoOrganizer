<?php

use yii\helpers\Html;

$this->title = 'Photo organizer';
?>

<div class="site-index">

    <div class="body-content text-center">
    
    	<br><br>
    	
    	<h1>Organize and store your photos in entirely new way with Photo Organizer Application.</h1>
    	
    	<br><br>
    	
    	<div>
    		<ul class="list-inline">
    			<li><?= Html::a("Learn More About Photo Organizer", ['/site/about'], ['class' => 'btn btn-default btn-larger']); ?></li>
    			<?= (Yii::$app->user->isGuest
    				? '<li>' . Html::a('Sign Up', ['/user/signUp'], ['class' => 'btn btn-default btn-larger']) . '</li>'
    				: '<li>' . Html::a('Upload Photos', ['/photos/index'], ['class' => 'btn btn-default btn-larger']) . '</li>'); ?>
    		</ul>
    	</div>

    </div>
    
</div>

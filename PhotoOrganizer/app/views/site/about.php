<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About Photo Organizer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <br><br>
	
	<div class="col-md-10 centered">
		
		<h4>
			Photo Organizer is an online photo management and sharing application. It is also a new, up-to-date
			application with a responsive view, has two main goals:
		</h4>
		
		<br>
		
		<div class="well">
		
		<b>1. We want to help people make their photos, life moments available to the people who matter to them.</b>
		<br><br>
		Maybe you want to keep a blog of moments captured on your cameraphone, or maybe you want to show off your best pictures to the whole world. Or maybe you want to securely and privately share photos of your kids with your family across the country or with your friends. Photo organizer makes all these things possible and more!
		<br><br>
		To do this, we want to get photos as many ways as we can: from the web, from mobile devices thanks for the responsive view and from the users' home computers.
		
		</div>
		
		<div class="well">
		
		<b>2. We want to enable new ways of organizing photos and video.</b>
		<br><br>
		Once you make the switch to digital, it is all too easy to get overwhelmed with the sheer number of photos you take.
		<br><br>
		Part of the solution is to make the process of organizing photos collaborative if you are member of a group. In Photo organizer, you can give your friends, family, and other contacts permission to organize your stuff - not just to add comments, but also notes and tags.
		<br><br>
		And you can organize your photos  more different way for example by the upload date, photo's extension, photo's visibility (private or public) and photos's size.
		</div>
		
		<div class="well">
		
  			<h4><b>Our Team:</b></h4>
  			<br><br>
  			
  			<div id="teamMemberContainer">
  			
  				<?php foreach ($employees as $employee):?>
  			
		  			<div class="teamMember">
		  				<?= Html::a(Html::img('@web/' . $employee->profile_picture_path, ['id' => 'TeamProfilePicture']), ['site/about/employees/profile/' . $employee->employee_id]); ?>
		  				<p class="text-center "><b><?= $employee->user_name ?></b></p>
		  			</div>
		  		
		  		<?php endforeach; ?>

	  			<br id="ClearBoth">
  			
  			</div>
  			
		</div>
		
	</div>
   
</div>
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
			Photo Organizer - an online photo management and sharing application - is a new, up-to-date
			application with a responsive view, has two main goals:
		</h4>
		
		<div class="well">
		
		<b>1. We want to help people make their photos available to the people who matter to them.</b>
		<br><br>
		Maybe they want to keep a blog of moments captured on their cameraphone, or maybe they want to show off their best pictures or video to the whole world in a bid for web celebrity. Or maybe they want to securely and privately share photos of their kids with their family across the country. Flickr makes all these things possible and more!
		<br><br>
		To do this, we want to get photos and video into and out of the system in as many ways as we can: from the web, from mobile devices, from the users' home computers and from whatever software they are using to manage their content. And we want to be able to push them out in as many ways as possible: on the Flickr website, in RSS feeds, by email, by posting to outside blogs or ways we haven't thought of yet. What else are we going to use those smart refrigerators for?
		
		</div>
		
		<div class="well">
		
		<b>2. We want to enable new ways of organizing photos and video.</b>
		<br><br>
		Once you make the switch to digital, it is all too easy to get overwhelmed with the sheer number of photos you take or videos you shoot with that itchy trigger finger. Albums, the principal way people go about organizing things today, are great -- until you get to 20 or 30 or 50 of them. They worked in the days of getting rolls of film developed, but the "album" metaphor is in desperate need of a Florida condo and full retirement.
		<br><br>
		Part of the solution is to make the process of organizing photos or videos collaborative. In Flickr, you can give your friends, family, and other contacts permission to organize your stuff - not just to add comments, but also notes and tags. People like to ooh and ahh, laugh and cry, make wisecracks when sharing photos and videos. Why not give them the ability to do this when they look at them over the internet? And as all this info accretes as metadata, you can find things so much easier later on, since all this info is also searchable.
		
		</div>
		
		<div class="well">
		
  			<h4><b>Our Team:</b></h4>
  			<br><br>
  			
  			<div id="teamMemberContainer">
  			
	  			<div class="teamMember">
	  				<?= Html::a(Html::img('@web/images/boti_profile_picture.jpg', ['id' => 'TeamProfilePicture']), ['']); ?>
	  				<p class="text-center "><b>Boti</b></p>
	  			</div>

	  			<br id="ClearBoth">
  			
  			</div>
  			
		</div>
		
	</div>
   
</div>
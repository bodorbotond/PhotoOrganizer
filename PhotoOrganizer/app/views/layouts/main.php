<?php

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerCssFile('@web/css/layout.css');

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    
    $profilePicturePath = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->profile_picture_path !== NULL ?
     													  '@web/' . Yii::$app->user->identity->profile_picture_path : 
    													  '@web/images/profile_picture.png';
    
    NavBar::begin([
        'brandLabel' 	=> 'Photo Organizer',
    	'brandUrl' 		=> null,
        'options' 		=> [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' 		=> [
        		'class' => 'navbar-nav navbar-right'
        ],
    	'encodeLabels' 	=> false,
        'items' 		=> [
        	[
        		'label'		=> '<li>'
        							. Html::beginForm(['/site/search'], 'post', ['class' => 'navbar-form'])
        								. '<div class="input-group">'
	        								. Html::textInput('SearchText', '', ['class' => 'form-control', 'placeholder' => 'Search'])
				        					. '<div class="input-group-btn">'
		        								. Html::submitButton(
					        						'<i class="glyphicon glyphicon-search"></i>',
					        						['class' => 'btn btn-default']
					        					)
	        								. '</div>'
        								. '</div>'
			        				. Html::endForm()
        						. '</li>'
        	],
            [
            	'label' 	=> '<span class="glyphicon glyphicon-home"></span> Home',
            	'url'	 	=> ['/']            		
            ],
            [
            	'label' 	=> '<span class="glyphicon glyphicon-info-sign"></span> About',
            	'url' 		=> ['/site/about']
            		
            ],
            [
            	'label' 	=> '<span class="glyphicon glyphicon-envelope"></span> Contact',
            	'url' 		=> ['/site/contact']
            		
            ],
        	[
        		'label'		=> '<span class="glyphicon glyphicon-picture"></span> Photos',
        		'url'		=> ['/photos/index'],
        		'visible'	=> !Yii::$app->user->isGuest,	
        	],
        	[
        		'label'		=> '<span class="glyphicon glyphicon-picture"></span> <span class="glyphicon glyphicon-picture"></span> Albums',
        		'url'		=> ['/albums/index'],
        		'visible'	=> !Yii::$app->user->isGuest,
        	],
        	[
        		'label'		=> '<span class="glyphicon glyphicon-user"></span><span class="glyphicon glyphicon-user"></span> Groups',
        		'url'		=> ['/groups/index'],
        		'visible'	=> !Yii::$app->user->isGuest,
        	],
            Yii::$app->user->isGuest ? (
            [	
                'label' 	=> '<span class="glyphicon glyphicon-log-in"></span> Login',
                'url' 		=> ['/user/login']            		
        	]
            ) : (
            [
        		'label' 	=> Html::img($profilePicturePath, ['class' => 'img-circle', 'id' => 'ProfilePictureInMenu']),
        		'items' 	=> [
			        			[
			        				'label' 	=> '<div id="DropdownAccountInfo">'
			        									. Html::img($profilePicturePath, ['class' => 'img-circle ProfilePictureInDropdownAccountInfo', 'id' => 'ProfilePictureInDropdownAccountInfo'])
			        							   		. '<span>'
			        										. '<br>&nbsp&nbsp'
			        										. Yii::$app->user->identity->user_name
			        										. '<br>&nbsp&nbsp'
			        										. Yii::$app->user->identity->e_mail
			        									. '</span>
			        								</div>',
			        				'url' 		=> null			        					
			        			],
        						'<br><li class="divider"></li>',
			        			[
			        				'label' 	=> 	'<ul class="list-inline">
				        								<li>' . Html::a('<span class="glyphicon glyphicon-user"></span> Account Info', ['/account/index'], ['class'=>'btn btn-default']) . '</li> 
				        								<li>'
								        					. Html::beginForm(['/user/logout'], 'post', ['class' => 'navbar-form'])
								        					. Html::submitButton(
								        						'<span class="glyphicon glyphicon-log-out"></span> Logout',
								        						['class' => 'btn btn-default']
								        					)
								        					. Html::endForm()
								        				. '</li>
			        								</ul>'
			        			],
        		],
        	]
            ),
        	
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container bimg">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
   </div>
</div>

<footer class="footer">
    
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

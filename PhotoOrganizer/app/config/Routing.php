<?php

return Array(
	'class' => 'yii\web\UrlManager',
	'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => Array (
    		
		'/' 			=> 'site/index',
    		
    	"site/about" 	=> "site/about",
		"site/contact" 	=> "site/contact",
    		
    	'user/login'			=> 'user/login',
    	'user/logout'			=> 'user/logout',
    	'user/signUp'			=> 'user/sign-up',
    	'user/verification'		=> 'user/sign-up-verification',
    	'account/index'			=> 'account/index',
    	'account/changeAccountPersonalInfo' => 'account/change-account-personal-info',
		

    ),
);

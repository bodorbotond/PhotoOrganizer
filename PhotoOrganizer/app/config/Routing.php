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
    		
    	'user/login'				=> 'user/login',
    	'user/loginVerification'	=> 'user/login-verification',
    	'user/logout'				=> 'user/logout',
    	'user/signUp'				=> 'user/sign-up',
    	'user/signUpVerification'	=> 'user/sign-up-verification',
    		
    	'account/index'						=> 'account/index',
    	'account/modifyPersonalInfo' 		=> 'account/modify-personal-info',
    	'account/deleteProfilePicture'		=> 'account/delete-profile-picture',
    	'account/addOrModifyRecoveryEmail'	=> 'account/add-or-modify-recovery-email',
    	'account/deleteRecoveryEmail'		=> 'account/delete-recovery-email',
    	'account/changePassword'			=> 'account/change-password',
    	'account/twoStepVerification'		=> 'account/two-step-verification',
		

    ),
);

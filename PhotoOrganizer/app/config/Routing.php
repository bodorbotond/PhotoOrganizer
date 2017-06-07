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
    	//'user/sendVerificationCode' => 'user/send-verification-code',
    	'user/userIdentify'			=> 'user/user-identify',
    	'user/forgotPassword'							=> 'user/forgot-password',
    	'user/forgotPassword/sendToEmailAdress'			=> 'user/forgot-password-send-to-email-adress',
    	'user/forgotPassword/sendToRecoveryEmailAdress'	=> 'user/forgot-password-send-to-recovery-email-adress',
    	'user/forgotPassword/verificationKey'			=> 'user/forgot-password-verification-key',
    	'user/forgotPassword/securityQuestions'			=> 'user/forgot-password-security-questions',
    	'user/forgotPassword/oldPasswords'				=> 'user/forgot-password-old-passwords',
    	'user/forgotPassword/changePassword'			=> 'user/forgot-password-change-password',
    		
    	'account/index'						=> 'account/index',
    	'account/modifyPersonalInfo' 		=> 'account/modify-personal-info',
    	'account/deleteProfilePicture'		=> 'account/delete-profile-picture',
    	'account/addOrModifyRecoveryEmail'	=> 'account/add-or-modify-recovery-email',
    	'account/deleteRecoveryEmail'		=> 'account/delete-recovery-email',
    	'account/changePassword'			=> 'account/change-password',
    	'account/twoStepVerification'		=> 'account/two-step-verification',
    	'account/addSecurityQuestions'		=> 'account/add-security-questions',
    	'account/modifySecurityQuestions'	=> 'account/modify-security-questions',
    	'account/deleteSecurityQuestions'	=> 'account/delete-security-questions',
		

    ),
);

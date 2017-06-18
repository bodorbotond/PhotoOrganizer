<?php

return Array(
	'class' => 'yii\web\UrlManager',
	'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => Array (
    		
		'/' => 'site/index',
    		
    	"site/about" 							=> "site/about",
    	"site/about/employees/profile/<id:\d+>"	=> "site/view-employee-profile",
		"site/contact" 							=> "site/contact",
    		
    	'user/login'						=> 'user/login',
    	'user/login/loginVerification'		=> 'user/login-verification',
    	'user/logout'						=> 'user/logout',
    	'user/signUp'						=> 'user/sign-up',
    	'user/signUp/signUpVerification'	=> 'user/sign-up-verification',
    	//'user/sendVerificationCode' => 'user/send-verification-code',
    	'user/userIdentify'								=> 'user/user-identify',
    	'user/forgotPassword'							=> 'user/forgot-password',
    	'user/forgotPassword/sendToEmailAdress'			=> 'user/forgot-password-send-to-email-adress',
    	'user/forgotPassword/sendToRecoveryEmailAdress'	=> 'user/forgot-password-send-to-recovery-email-adress',
    	'user/forgotPassword/verificationKey'			=> 'user/forgot-password-verification-key',
    	'user/forgotPassword/securityQuestions'			=> 'user/forgot-password-security-questions',
    	'user/forgotPassword/oldPasswords'				=> 'user/forgot-password-old-passwords',
    	'user/forgotPassword/changePassword'			=> 'user/forgot-password-change-password',
    		
    	'account/index'									=> 'account/index',
    	'account/perosnalInfo/modify' 					=> 'account/modify-personal-info',
    	'account/perosnalInfo/deleteProfilePicture'		=> 'account/delete-profile-picture',
    	'account/security/changePassword'				=> 'account/change-password',
    	'account/security/recoveryEmail/addOrModify'	=> 'account/add-or-modify-recovery-email',
    	'account/security/recoveryEmail/delete'			=> 'account/delete-recovery-email',
    	'account/security/twoStepVerification'			=> 'account/two-step-verification',
    	'account/security/securityQuestions/add'		=> 'account/add-security-questions',
    	'account/security/securityQuestions/modify'		=> 'account/modify-security-questions',
    	'account/security/securityQuestions/delete'		=> 'account/delete-security-questions',
    		
    	'photos/index'							=> 'photos/index',
    	'photos/show/extension'					=> 'photos/show-by-extension',
    	'photos/show/size'						=> 'photos/show-by-size',
    	'photos/show/visibility'				=> 'photos/show-by-visibility',
    	'photos/show/uploadDate'				=> 'photos/show-by-upload-date',
    	'photos/select/<a:\w+>'					=> 'photos/select',
    	'photos/selectAddTo/<a:\w+>,<id:\d+>'	=> 'photos/select-add-to',
    	'photos/editOne/<id:\d+>'				=> 'photos/edit-one-photo',
    	'photos/editMore'						=> 'photos/edit-more-photo',
		
		'albums/index'					=> 'albums/index',
    	'albums/create'					=> 'albums/create-album',
    	'albums/edit/<id:\d+>'			=> 'albums/edit-album',
    	'albums/delete/<id:\d+>'		=> 'albums/delete-album',
    	'albums/viewAlbum/<id:\d+>'		=> 'albums/view-album',
    	'albums/removePhotos/<id:\d+>'	=> 'albums/remove-photos',
    		
    	'groups/index'				=> 'groups/index',
    	'groups/create'				=> 'groups/create-group',
    	'groups/edit/<id:\d+>'		=> 'groups/edit-group',
    	'groups/delete/<id:\d+>'	=> 'groups/delete-group',
    	'groups/viewGroup/<id:\d+>'	=> 'groups/view-group',
    		
    ),
);

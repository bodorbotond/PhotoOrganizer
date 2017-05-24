<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Modify Account';
$this->params['breadcrumbs'][] = ['label' => 'Account Info', 'url' => ['/account/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to modify account:</p>

    <?php 
    
    $model->gender = Yii::$app->user->identity->gender;			// set user's gender (equal to gender from database)
    
    $form = ActiveForm::begin([
        'id' => 'modify-account-form',
        'options' => [
        		'class' 	=> 'form-horizontal',
        		'enctype' 	=> 'multipart/form-data'
        ],
        'fieldConfig' => [
            'template' 		=> "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' 	=> ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
		
		<!-- set user current datas by default values -->
		
        <?= $form->field($model, 'userName')->textInput(['value' => Yii::$app->user->identity->user_name]) ?>
        
        <?= $form->field($model, 'firstName')->textInput(['value' => Yii::$app->user->identity->first_name]) ?>
        
        <?= $form->field($model, 'lastName')->textInput(['value' => Yii::$app->user->identity->last_name]) ?>
        
        <?= $form->field($model, 'eMail')->textInput(['value' => Yii::$app->user->identity->e_mail]) ?>
        
        <?=	$form->field($model, 'gender')->radioList([
        												'Female' => 'Female',
        												'Male' 	=> 'Male'
        											   ]);
        ?>
        
        <?= $form->field($model, 'profilePicture')->fileInput()?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Modify', ['class' => 'btn btn-primary', 'name' => 'modify-account-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
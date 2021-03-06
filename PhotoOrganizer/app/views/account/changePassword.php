<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = ['label' => 'Account Info', 'url' => ['/account/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-change-password">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>Please fill out the following fields to change your password:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'change-password-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    
    	<?= $form->field($model, 'oldPassword')->passwordInput() ?>

        <?= $form->field($model, 'newPassword')->passwordInput() ?>
        
        <?= $form->field($model, 'confirmedNewPassword')->passwordInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton( 'Change', ['class' => 'btn btn-primary', 'name' => 'change-password-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

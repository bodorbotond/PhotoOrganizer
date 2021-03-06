<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login Verification';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/user/login']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login-verification">
    <h1><?= Html::encode($this->title) ?></h1>
	
	<p>We sent you an email with your verification key. If you enter a wrong verification key or reload page 
	you have to login again. Please do not reload page.</p>
    <p>Please fill out the following field to verification:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-verification-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'verificationKey')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Verify', ['class' => 'btn btn-primary', 'name' => 'login-verification-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

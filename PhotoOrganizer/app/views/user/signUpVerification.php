<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Sign Up Verification';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/user/login']];
$this->params['breadcrumbs'][] = ['label' => 'Sign Up', 'url' => ['/user/signUp']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-sign-up-verification">
    <h1><?= Html::encode($this->title) ?></h1>
	
	<p>We sent you an email with your verification key.</p>
    <p>Please fill out the following field to verification:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'sign-up-verification-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'verificationKey')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Verify', ['class' => 'btn btn-primary', 'name' => 'sign-up-verification-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

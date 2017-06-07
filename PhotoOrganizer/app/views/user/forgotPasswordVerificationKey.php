<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'FP Verification Key';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/user/login']];
$this->params['breadcrumbs'][] = ['label' => 'User Identify', 'url' => ['/user/userIdentify']];
$this->params['breadcrumbs'][] = ['label' => 'Forgot Password', 'url' => ['/user/forgotPassword']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-fp-verification-key">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to change your password:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'fp-verification-key-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
		
		<?= $form->field($model, 'verificationKey')->textInput() ?>
		
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Verify', ['class' => 'btn btn-primary', 'name' => 'fp-verification-key-button']) ?>
            </div>
        </div>
     
	<?php ActiveForm::end(); ?>

</div>

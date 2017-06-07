<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'User Identify';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/user/login']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-user-identify">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>Please fill out the following field to identify your account:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'user-identify-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'usernameOrEmail')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Identify', ['class' => 'btn btn-primary', 'name' => 'user-identify-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

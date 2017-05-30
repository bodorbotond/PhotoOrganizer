<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Recovery Email';
$this->params['breadcrumbs'][] = ['label' => 'Account Info', 'url' => ['/account/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-add-or-modify-recovery-email">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following field to
    	<?php echo (Yii::$app->user->identity->recovery_e_mail !== NULL ? 'modify' : 'add') ?> 
    	recovery e-mail:
    </p>

    <?php $form = ActiveForm::begin([
        'id' => 'add-or-modify-recovery-email-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'recoveryEmail')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton( (Yii::$app->user->identity->recovery_e_mail !== NULL ? 'Modify' : 'Add'),
                						['class' => 'btn btn-primary', 'name' => 'add-or-modify-recovery-email-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

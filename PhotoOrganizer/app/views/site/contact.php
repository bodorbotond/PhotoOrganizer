<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Thank you for contacting us. We will respond to you as soon as possible.
        </div>

    <?php else: ?>

    <p>
	    If you have business inquiries or other questions, please fill out the following form to contact us.
	    Thank you.
    </p>


    <?php $form = ActiveForm::begin([
							    'id' => 'contact-form',
							    'options' => ['class' 	=> 'form-horizontal'],
							    'fieldConfig' => [
												'template' 		=> "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
												'labelOptions' 	=> ['class' => 'col-lg-1 control-label'],
								],                		
    ]); ?>

    	<?= $form->field($model, 'name')->textInput() ?>

    	<?= $form->field($model, 'eMail')->textInput() ?>

   		<?= $form->field($model, 'subject')->textInput() ?>

        <?= $form->field($model, 'body')->textArea(['rows' => 8]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
            	<?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
           </div>
       </div>

	<?php ActiveForm::end(); ?>

    <?php endif; ?>
    
</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Security Question';
$this->params['breadcrumbs'][] = ['label' => 'Account Info', 'url' => ['/account/index']];
$this->params['breadcrumbs'][] = $this->title;


// check user want to add question or modify question
if (count($securityQuestionIdsAndAnswers) === 0)		// if user want to add => default values for form
{
	$firstQuestionId = 1;
	$secondQuestionId = 1;
	$firstAnswer = '';
	$secondAnswer = '';
}
else 													// if user want to modify => the already insert datas as default values for form
{
	$firstQuestionId = $securityQuestionIdsAndAnswers[0]['question_id'];
	$secondQuestionId = $securityQuestionIdsAndAnswers[1]['question_id'];
	$firstAnswer = $securityQuestionIdsAndAnswers[0]['answer'];
	$secondAnswer = $securityQuestionIdsAndAnswers[1]['answer'];
}

?>

<div class="site-add-or-modify-security-question">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to 
    	<?= (count($securityQuestionIdsAndAnswers) === 0 ? 'add' : 'modify')?>
     	security questions:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'add-or-modify-security-question-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    
    	<?= $form->field($model, 'firstQuestion')->dropDownList($securityQuestions,
    															['options' => [$firstQuestionId => ['selected' => true]]]); ?>
    	
    	<?= $form->field($model, 'firstAnswer')->textInput(['value' => $firstAnswer]); ?>
    	
    	<?= $form->field($model, 'secondQuestion')->dropDownList($securityQuestions,
    															 ['options' => [$secondQuestionId => ['selected' => true]]]); ?>  
    	
    	<?= $form->field($model, 'secondAnswer')->textInput(['value' => $secondAnswer]); ?> 

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton((count($securityQuestionIdsAndAnswers) === 0 ? 'Add' : 'Modify'),
                					   ['class' => 'btn btn-primary', 'name' => 'add-or-modify-security-question-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

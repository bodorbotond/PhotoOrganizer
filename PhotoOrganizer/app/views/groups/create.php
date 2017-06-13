<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create Group';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-create-group">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to create group:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'create-group-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
 		<?= $form->field($model, 'groupName')->textInput(); ?>
    	
    	<?= $form->field($model, 'groupVisibility')->dropDownList([
    																'private'	=> 'private',
    																'public'	=> 'public',
    	]); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create Group', ['class' => 'btn btn-primary', 'name' => 'create-group-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

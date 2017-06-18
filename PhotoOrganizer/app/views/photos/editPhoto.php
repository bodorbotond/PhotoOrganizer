<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Photo';
$this->params['breadcrumbs'][] = ['label' => 'Photos', 'url' => ['/photos/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-edit-photo">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to edit photo:
    </p>

    <?php $form = ActiveForm::begin([
        'id' => 'edit-photo-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' 	=> ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
    	<?= $form->field($model, 'title')->textInput(['value' => $photo->photo_title]); ?>
    	
    	<?= $form->field($model, 'tag')->textInput(['value' => $photo->photo_tag]); ?>
    	
    	<?= $form->field($model, 'description')->textArea(['value' => $photo->photo_description, 'rows' => 6]) ?>
    	
    	<?= $form->field($model, 'visibility')->dropDownList([
    															'private'	=> 'private',
    															'public'	=> 'public',
    														  ],
    														  ['options' => [$photo->photo_visibility => ['selected' => true]]]
    	); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Edit Photo', ['class' => 'btn btn-primary', 'name' => 'edit-photo-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

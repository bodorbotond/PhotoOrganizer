<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create Album';
$this->params['breadcrumbs'][] = ['label' => 'My Albums', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-create-album">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to create album:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'create-album-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
    	<?= $form->field($model, 'albumName')->textInput(); ?>
    	
    	<?= $form->field($model, 'albumVisibility')->dropDownList([
    																'private'	=> 'private',
    																'public'	=> 'public',
    	]); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create Album', ['class' => 'btn btn-primary', 'name' => 'create-album-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

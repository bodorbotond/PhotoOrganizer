<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Edit Album';
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['/albums/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="edit-create-album">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following fields to edit album:
     </p>

    <?php $form = ActiveForm::begin([
        'id' => 'edit-album-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
    	<?= $form->field($model, 'albumName')->textInput(['value' => $album->album_name]); ?>
    	
    	<?= $form->field($model, 'albumVisibility')->dropDownList([
    																'private'	=> 'private',
    																'public'	=> 'public',
    															   ],
    															   ['options' => [$album->album_visibility => ['selected' => true]]]
    	); ?>  

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Edit Album', ['class' => 'btn btn-primary', 'name' => 'edit-album-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

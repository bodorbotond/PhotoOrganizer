<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-search">
    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
    	Please fill out the following field to search:
    </p>

    <?php $form = ActiveForm::begin([
        'id' => 'search-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' 	=> ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    	
    	<?= $form->field($model, 'searchText')->textInput(); ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>

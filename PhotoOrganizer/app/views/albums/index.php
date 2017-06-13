<?php

use yii\helpers\Html;

$this->title = 'My Albums';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-my-albums">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= Html::a('Create Album', ['/albums/create'], ['class' => 'btn btn-default']) ?>
    
</div>
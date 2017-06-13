<?php

use yii\helpers\Html;

$this->title = 'Groups';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-groups">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= Html::a('Create Group', ['/groups/create'], ['class' => 'btn btn-default']) ?>
    
</div>

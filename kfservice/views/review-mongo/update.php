<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\ReviewMongo */

$this->title = 'Update Review Mongo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Review Mongos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="review-mongo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

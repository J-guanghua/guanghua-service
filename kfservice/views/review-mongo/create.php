<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\ReviewMongo */

$this->title = 'Create Review Mongo';
$this->params['breadcrumbs'][] = ['label' => 'Review Mongos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-mongo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

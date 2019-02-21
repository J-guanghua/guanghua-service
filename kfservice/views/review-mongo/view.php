<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\ReviewMongo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Review Mongos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-mongo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fromusername',
            'services_id',
            'review_level',
            'describe',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\WxServices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-services-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'avatar',
            'status',
            'sign',
            'appid',
            'created_at',
            'updated_at',
            'reception_num',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\External */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Externals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="external-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'domain_name',
            'message_card',
            'commodity',
            'order',
            'browse',
            'appid',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

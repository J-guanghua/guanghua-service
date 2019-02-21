<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\RegisterOvertime */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Register Overtimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-overtime-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fromusername',
            'services_id',
            'message_id',
            'overtime:datetime',
            'created_at',
        ],
    ]) ?>

</div>

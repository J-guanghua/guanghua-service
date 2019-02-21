<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\RegisterOvertime */

$this->title = 'Update Register Overtime: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Register Overtimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="register-overtime-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

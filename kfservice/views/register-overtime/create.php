<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\RegisterOvertime */

$this->title = 'Create Register Overtime';
$this->params['breadcrumbs'][] = ['label' => 'Register Overtimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-overtime-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

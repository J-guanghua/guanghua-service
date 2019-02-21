<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\search\ExternalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="external-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'domain_name') ?>

    <?= $form->field($model, 'message_card') ?>

    <?= $form->field($model, 'commodity') ?>

    <?= $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'browse') ?>

    <?php // echo $form->field($model, 'appid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

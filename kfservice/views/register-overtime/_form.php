<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\RegisterOvertime */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="register-overtime-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fromusername')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'services_id')->textInput() ?>

    <?= $form->field($model, 'message_id')->textInput() ?>

    <?= $form->field($model, 'overtime')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

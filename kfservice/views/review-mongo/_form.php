<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\ReviewMongo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-mongo-form">

    <?php $form = ActiveForm::begin([
        'action'=>'http://120.79.179.32/v1/review/create',
        'method' => 'post',
    ]); ?>
    <input type="text" name="fromusername">
    <input type="text" name="services_id">
    <input type="text" name="review_level">
    <input type="text" name="describe">
    <input type="text" name="created_at">
<!--     <?= $form->field($model, 'fromusername')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'services_id')->textInput() ?>

    <?= $form->field($model, 'review_level')->dropDownList([ '差评' => '差评', '中评' => '中评', '好评' => '好评', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'describe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

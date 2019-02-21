<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model common\modules\bonus\models\search\VerifyBounsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-verify-bouns-search">

   <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ])->label('时间筛选');
    ?>
    <?= $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ])->label('-');;
    ?>
    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
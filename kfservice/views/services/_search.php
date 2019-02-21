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

    <?= $form->field($model, 'id')->dropDownList(\backend\modules\kfservice\search\RelevanceSearch::services(),['prompt' => '全部'])->label('客服') ?>

    <?= $form->field($model, 'status')->dropDownList($model->userStatus(),['prompt' => '全部']) ?>
    <?= $form->field($model, 'online_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ])->label('时间筛选');
    ?>
    <?= $form->field($model, 'offline_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ])->label('-');;
    ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
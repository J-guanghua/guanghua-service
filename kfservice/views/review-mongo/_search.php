<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\search\ReviewMongoSearch */
/* @var $form yii\widgets\ActiveForm */
?>
    
<div class="review-mongo-search">

   <?php
    Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'username')->label('微信昵称') ?>

    <?= $form->field($model, 'id')->dropDownList(\backend\modules\kfservice\search\RelevanceSearch::services(),['prompt' => '全部'])->label('客服') ?>

    <?= $form->field($model, 'review_level')->dropDownList($model::$reviewLevel,['prompt'=>'请选择...']) ?>
    <!-- <?= $form->field($model, 'fromusername')?> -->
    <?= $form->field($model, 'created_at')->widget(DateRangePicker::className(),[
        'convertFormat'=>true,
        'pluginOptions'=>[
            'timePicker'=>true,
            'timePickerIncrement'=>15,
            'locale'=>['format'=>'Y-m-d h:i']
        ]            
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

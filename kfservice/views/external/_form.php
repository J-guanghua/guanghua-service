<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model forum\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin([]); ?>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">接口设置</a></li>
                <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">系统设置</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                <?= $form->field($model, 'domain_name')->textInput(['maxlength' => true]) ?>
		
		<?= $form->field($model, 'http_link')->textInput(['maxlength' => true]) ?>
                
		<?= $form->field($model, 'userinfo')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'message_card')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'commodity')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'browse')->textInput(['maxlength' => true]) ?>
                
		</div>

                <div class="tab-pane" id="tab_2">
                <?= $form->field($model, 'is_receive')->radioList(['0' => '否','1'=>'是']) ?>
                <?= $form->field($model, 'wicket')->radioList(['0' => '否','1'=>'是']) ?>
                <?= $form->field($model, 'access_way')->radioList(['0' => '自动接入','1'=>'手动接入']) ?>
                <?= $form->field($model, 'grade_limit')->radioList(['0' => '一天/次','1'=>'不限制']) ?>
                <?= $form->field($model, 'overtime')->input('number') ?>
                <?= $form->field($model, 'grade_msg')->textarea() ?>

                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '发布' : '更新', ['class' => 'btn bg-maroon btn-flat btn-block']) ?>
        </div>

    <?php ActiveForm::end(); ?>

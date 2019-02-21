<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\WxServices */
/* @var $form yii\widgets\ActiveForm */
$array = [];
foreach ($model::$identity as $key => $value) {
	$array[$value] = $value;
}
?>

<div class="wx-services-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'avatar')->widget('common\widgets\file_upload\FileUpload') ?>
    <?= $form->field($model, 'identity')->dropDownList($array, ['prompt' => '全部'])  ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

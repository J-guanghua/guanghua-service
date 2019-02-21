<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\External */

$this->title = '更新: 外部网站数据对接';
?>
<div class="external-update">

<div class="external-form">

    <?php $form = ActiveForm::begin(); ?>
 
    <?= $form->field($model, $name)->textInput(['maxlength' => true])?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>

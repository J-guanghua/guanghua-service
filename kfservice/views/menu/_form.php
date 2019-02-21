<?php

use rbac\AutocompleteAsset;
use rbac\models\Menu;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rbac\models\Menu */
/* @var $form yii\widgets\ActiveForm */

?>

    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
            <?php if(\Yii::$app->request->get('category')==null):?>
            <?= $form->field($model, 'parent')->dropDownList($model::getDropDownList(\common\helpers\Tree::build($model::find()->asArray()->all(), 'id', 'parent', 'children', null)), ['encode' => false, 'prompt' => '请选择']) ?>
            <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
            <?php endif;?>
            <?= $form->field($model, 'order')->input('number') ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => 'btn btn-flat btn-block bg-maroon']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php


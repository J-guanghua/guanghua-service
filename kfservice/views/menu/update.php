<?php


/* @var $this yii\web\View */
/* @var $model rbac\models\Menu */

$this->title = '更新 : '.' '.$model->name;
$this->params['breadcrumbs'][] = ['label' => '快捷语', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="menu-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

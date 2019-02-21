<?php


/* @var $this yii\web\View */
/* @var $model rbac\models\Menu */

$this->title = '添加快捷语';
$this->params['breadcrumbs'][] = ['label' => '快捷语', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

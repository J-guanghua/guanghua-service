<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\WxServices */

$this->title = '添加客服';
$this->params['breadcrumbs'][] = ['label' => '客服', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-services-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

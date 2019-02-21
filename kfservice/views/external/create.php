<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\kfservice\models\External */

$this->title = '客服系统设置';
$this->params['breadcrumbs'][] = ['label' => 'Externals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="external-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

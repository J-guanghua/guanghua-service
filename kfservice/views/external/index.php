<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kfservice\search\ExternalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '外部网站数据对接';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="external-index">
<div class="panel panel-default row">
  <div class="panel-body col-lg-10">
        <?php if($dataProvider->query->count() == 0):?>
            <p>
                <?= Html::a('创建对接网站', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        <?php endif;?>
  </div>
  <div class="panel-body  col-lg-2">
    <div class="col-lg-1">
       <?= Html::a('重启客服系统', ['reload'], ['class' => 'btn btn-success']) ?>
    </div>
  </div>
</div>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'domain_name',
            'message_card',
            'commodity',
            'order',
            // 'browse',
            // 'appid',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

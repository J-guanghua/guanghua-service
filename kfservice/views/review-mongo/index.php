<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kfservice\search\ReviewMongoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户评分';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-mongo-index">
<div class="panel panel-default row">
  <div class="panel-body col-lg-11">
   <?php echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
</div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'storage.username',
            ],
            [
                'attribute'=>'storage.avatar',
                'value'=>function($model){
                    return Html::img($model->storage==null?null:$model->storage->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            'services.username',
            [
                'attribute'=>'avatar',
                'value'=>function($model){
                    return Html::img($model->services==null?null:$model->services->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            'fromusername',
            'review_level',
            'describe',
            [
                'attribute'=>'created_at',
                'value'=>function($model){
                    return date('Y-m-d H:i:s',$model->created_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

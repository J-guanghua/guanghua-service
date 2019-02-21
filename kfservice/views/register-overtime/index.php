<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kfservice\search\RegisterOvertimeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服超时回复记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-overtime-index">
<div class="panel panel-default row">
  <div class="panel-body col-lg-11">
   <?php echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
</div>
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
                    return Html::img($model->storage->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            [
                'attribute'=>'services_id',
                'value'=>function($model){
                    return $model->services->username;
                },
            ],
            [
                'attribute'=>'avatar',
                'value'=>function($model){
                    return Html::img($model->services->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            'fromusername',
            'message_id',
            [
                'attribute'=>'overtime',
                'value'=>function($model){
                    return (new \backend\modules\kfservice\search\ServicesSearch)->sec2time($model->overtime);
                }
            ],
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

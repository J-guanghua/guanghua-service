<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\wechat\search\SceneMobileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户手机登记列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default row">
  <div class="panel-body col-lg-11">
   <?php echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
</div>
<div class="scene-mobile-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'avatar',
                'value'=>function($model){
                    return Html::img($model->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            'username',
            'fromusername',
            'status',
            'fd',
            'appid',
            'identity',
            [
                'attribute'=>'created_at',
                'value'=>function($model){;    
                    return $model->updated_at <= 0 ? null : date("Y-m-d H:i:s",$model->created_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($model){;    
                    return $model->updated_at <= 0 ? null : date("Y-m-d H:i:s",$model->updated_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{create}',
                'buttons' => [
                    'create' => function($url, $model) {
                        return Html::a(Html::icon('fa fa-circle-o'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-xs','title'=>"绑定用户" ,'aria-label'=>"取消绑定", 'data-pjax'=>"0"]);
                    }
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

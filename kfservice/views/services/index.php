<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url; 
/* @var $this yii\web\View */
/* @var $searchModel home\modules\wechats\search\BusinessCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
    <?= $this->title . ' ' . '<div class="btn-group" role="group" aria-label="...">'
      .Html::a('查看客服订单统计',false,['class'=>'btn btn-default','id'=>"orderInfo"])?> 
<?php $this->endBlock() ?>

<div class="wx-business-card-index">

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute'=>'avatar',
                'value'=>function($model){
                    return Html::img($model->avatar,['width'=>50]);
                },
                'format'=>'raw'
            ],
            'username',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->userStatus()[$model->status];
                },
                'format'=>'raw'
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($model){;    
                    return $model->updated_at <= 0 ? null : date("Y-m-d H:i:s",$model->updated_at);
                },
            ],
            [
                'attribute'=>'sign',
                'value'=>function($model) use ($searchModel) {
                    return $searchModel->getMessage($model->id);
                },
                'format'=>'raw',
                'attribute'=>'总接待量'
            ],
            'reception_num',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<?php $this->beginBlock('js') ?>
<script>
$('#orderInfo').click(function(){
        layer.open({
        type: 2,
        maxmin: !0,
        title: "与 的聊天记录",
        area: ["100%", "100%"],
        shade: !1,
        offset: "rb",
        skin: "layui-box",
        anim: 2,
        id: "layui-layim-chatlog",
        content: 'https://background.camel.com.cn/index.php?controller=wxkf_socket&action=analysis&time_type=0'
    })
}) 
</script>
<?php $this->endBlock() ?>
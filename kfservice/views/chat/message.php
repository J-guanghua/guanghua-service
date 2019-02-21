<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
\backend\modules\kfservice\widgets\assets\CustomerAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\wechat\search\SceneMobileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服回话记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default row">
  <div class="panel-body col-lg-11">
    <div class="wx-verify-bouns-search">
       <?php
        Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
        $form = ActiveForm::begin([
            'action' => ['message'],
            'method' => 'get',
            'options' => ['class' => 'form-inline'],
        ]); ?>
        <?= $form->field($searchModel, 'nickname')?>
        <?= $form->field($searchModel, 'fromusername')?>
        <?= $form->field($searchModel, 'to_id')->dropDownList($searchModel::services(), ['prompt' => '全部'])  ?>
        <?= $form->field($searchModel, 'updated_at')->widget(DateRangePicker::className(),[
            'convertFormat'=>true,
            'pluginOptions'=>[
                'timePicker'=>true,
                'timePickerIncrement'=>15,
                'locale'=>['format'=>'Y-m-d h:i']
            ]            
        ]) ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
  </div>
</div>
<div class="scene-mobile-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'username',
                'value'=>function($model){;    
                    return $model->service===null ?:Html::img($model->service->avatar,['width'=>50],['style'=>'border-radius:20px']).$model->service->username;
                },
                'format'=>'raw'
            ],
            [
                'attribute'=>'nickname',
                'value'=>function($model){    
                    return $model->user===null?:"<div>"
                    .Html::img($model->user->avatar,['width'=>50],['style'=>'border-radius:20px']).
                    " {$model->user->username}<br/>{$model->user->fromusername}
                    </div>";
                },
                'format'=>'raw'
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){;    
                    return $model->status ? '接入会话.':'会话结束';
                },
            ],
            'last_msg',
            [
                'attribute'=>'created_at',
                'value'=>function($model){;    
                    return date("Y-m-d H:i:s",$model->created_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($model){;    
                    return date("Y-m-d H:i:s",$model->updated_at);
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view'=> function ($url, $model, $key){
                        $url = Url::to(['/kfservice/chat/history','id'=>$model->fo_id]);
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', false,['class'=>"messageInfo" ,'onclick'=>"messageInfo('$url')"]);
                    }
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<?php $this->beginBlock('js') ?>
<script>
function messageInfo(url){
    layer.open({
        type: 2,
        maxmin: !0,
        title: "与 的聊天记录",
        area: ["450px", "100%"],
        shade: !1,
        offset: "rb",
        skin: "layui-box",
        anim: 2,
        id: "layui-layim-chatlog",
        content: url
    })
}
layui.use('layim', function(layim){
  //基础配置
  layim.config({brief:true});
}); 
</script>
<?php $this->endBlock() ?>

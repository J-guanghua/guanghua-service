<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker; 
/* @var $this yii\web\View */
/* @var $searchModel home\modules\wechats\search\BusinessCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息管理';
?>
<style type="text/css">
.wrap > .container {
    padding: 0px 15px;
}
#w2{display: none;}
</style>
<div class="wx-business-card-index">

    <div class="panel panel-default row">
      <div class="panel-body col-lg-11">
       <div class="wx-verify-bouns-search">

               <?php
                Yii::$container->set(\yii\widgets\ActiveField::className(), ['template' => "{label}\n{input}\n{hint}"]);
                $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                    'options' => ['class' => 'form-inline'],
                ]); ?>
                <?= $form->field($searchModel, 'value')->textInput()->label('消息');
                ?>
                <?= $form->field($searchModel, 'created_at')->widget(DateTimePicker::classname(), [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ])->label('时间筛选');
                ?>
                <?= $form->field($searchModel, 'end_time')->widget(DateTimePicker::classname(), [
                        'options' => ['placeholder' => ''],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ])->label('-');;
                ?>
                <div class="form-group">
                    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
      </div>
    </div>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute'=>'avatar',
                    'value'=>function($model){
                        if($model->genre==2){
                            return Html::tag('div',Html::tag('div',Html::img(($model->service==null?null:$model->service->avatar),['width'=>50]).'<br/>'.($model->service==null?null:$model->service->username),['style'=>"float:left;width:60px"]).' <div style="color:green;float:left;line-height: 50px">===》</div> '.Html::tag('div',Html::img(($model->storage==null?null:$model->storage->avatar),['width'=>50]).'<br/>'.($model->storage==null?null:$model->storage->username),['style'=>"float:left;width:80px"]),['style'=>"width:230px"]);
                        }
                        return Html::tag('div',Html::tag('div',Html::img(($model->storage==null?null:$model->storage->avatar),['width'=>50]).'<br/>'.($model->storage==null?null:$model->storage->username),['style'=>"float:left;width:60px"]).' <div style="color:green;float:left;line-height: 50px">===》</div> '.Html::tag('div', Html::img(($model->service==null?null:$model->service->avatar),['width'=>50]).'<br/>'.($model->service==null?null:$model->service->username),['style'=>"float:left;width:80px"]),['style'=>"width:230px"]);
                    },
                    'format'=>'raw',
                    'label'=>'头像'
                ],
                'value',
                [
                    'attribute'=>'created_at',
                    'value'=>function($model){
                        return date("Y-m-d H:i:s",$model->created_at);
                    },
                    'format'=>'raw',
                    'label'=>'发送时间'
                ],
            ],
        ]); ?>
<?php Pjax::end(); ?></div>


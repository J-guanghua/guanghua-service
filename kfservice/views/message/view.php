<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url; 
/* @var $this yii\web\View */
/* @var $searchModel home\modules\wechats\search\BusinessCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会话管理';
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
       <?php echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
    </div>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute'=>'avatar',
                    'value'=>function($model){
                        
                        return Html::img($model->storage->avatar,['width'=>50]);
                    },
                    'format'=>'raw',
                    'label'=>'头像'
                ],
                [
                    'attribute'=>'fromusername',
                    'value'=>function($model){
                        return $model->storage->username;
                    },
                    'format'=>'raw',
                    'label'=>'微信昵称'
                ],
            ],
        ]); ?>
<?php Pjax::end(); ?></div>


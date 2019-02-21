<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel rbac\models\searchs\Menu */

$this->title = '快捷回复';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
.navbar-inverse{display: none;}
.wrap > .container {
    padding: 20px 15px 20px;
}
</style>
<div class="box-primary">
    <div class="box-body">
        <?= \backend\widgets\grid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'parent',
            'parentRootValue' => null, //first parentId value
            'pluginOptions' => [
                'initialState' => 'collapse',
            ],
            'columns' => [
                'name',
                [
                    'attribute'=>'data',
                    'value'=>function($model){
                        return $model->data==null?'':Html::tag('div',$model->data."<span class='guanghua glyphicon glyphicon-edit' data-data='".$model->data."'></span>",['class'=>'insert_message']);
                    },
                    'format'=>'raw'
                ],
            ],
        ]);
        ?>
    </div>
</div>



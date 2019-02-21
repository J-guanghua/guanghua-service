<?php

namespace backend\modules\kfservice\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\modules\kfservice\models\WxServices;
use backend\modules\kfservice\models\WxCustomer;
use backend\modules\kfservice\search\MessageSearch;
use yii\filters\AccessControl;
/**
 * Default controller for the `bonus` module
 */
class MessageController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->searchlist(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionView()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->searchs(Yii::$app->request->queryParams);
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}

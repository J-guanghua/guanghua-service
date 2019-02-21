<?php

namespace backend\modules\kfservice\controllers;

use Yii;
use yii\web\Controller;
use common\models\WxUser;
use yii\web\Response;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use backend\modules\kfservice\models\WxServices;
use backend\modules\kfservice\models\WxCustomer;
use backend\modules\kfservice\models\WxRelevance;
use backend\modules\kfservice\models\WxMessage;
use backend\modules\kfservice\models\WxConfig;
use backend\modules\kfservice\models\WxStorage;
use backend\modules\kfservice\search\MessageSearch;
use backend\modules\kfservice\search\CustomerSearch;
use backend\modules\kfservice\search\RelevanceSearch;
use yii\filters\AccessControl;
/**
 * Default controller for the `bonus` module
 */
class ChatController extends Controller
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

    public function actions()
    {
        return [
            'upload'=>[
                'class' => '\common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'isSwoole'=>true,
                    'imageFieldName'=>'file',
                    'imageFieldName'=>'file',
                    'imagePathFormat' => "/static/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'img-reader'=>[
                'class' => '\backend\modules\kfservice\actions\ImgReaderAction',     //这里扩展地址别写错
                'imagePath'=> "/static/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        return $this->render('index');
    }

    public function actionUserList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $user['status'] = 'online';
        $array = ['code'=>0,'msg'=>'ok','data'=>['mine'=>$user->toArray(),
        'friend'=>['0'=>['groupname'=>'微信用户','id'=> 1,'list'=>[]]]]];
        $i = 0;
        foreach (WxRelevance::find()
            ->where(['to_id'=>Yii::$app->user->id,'status'=>1])->orderBy(['unread'=>SORT_DESC])->all() as $key => $value) {
            if ($value->user !== null) {
                $userlist[$i] = $value->user->toArray();
                $userlist[$i]['unread'] = $value->unread;
                $userlist[$i]['timestamp'] = $value->updated_at;
                $userlist[$i]['for_time'] = $value->for_time*1000;
                $userlist[$i]['last_msg'] = $value->last_msg;
                $i++;
            } else {
                $value->delete();
            }
        };
        $array['data']['group']=$user->waitMessage();
        isset($userlist) ? $array['data']['friend'][0]['list'] = $userlist : []; 
        return $array;
    }

    /**
     * @return [type] [description]
     */
    public function actionRecord($to_id,$fromusername)
    {   
        if(null === $model = WxCustomer::findOne(['fromusername'=>$fromusername])) {
            throw new NotFoundHttpException('当前用户不存在或已被删除？');
        };
        return $this->render('history',[
                'model'=>$model
            ]);
    }
    /**
     * [actionHistory 会员记录]
     * @return [type] [description]
     */
    public function actionHistory($id)
    {   
        if(null === $model = WxCustomer::findOne(['id'=>(int) $id])) {
            throw new NotFoundHttpException('当前用户不存在或已被删除？');
        };
        return $this->render('history',[
                'model'=>$model
            ]);
    }

    /**
     * [actionHistory 消息历史记录]
     * @return [type] [description]
     */
    public function actionHistorys($to_id = null,$fromusername = null)
    {   
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (((0 < $page = Yii::$app->request->get('page')) || $page===null) && ($to_id!==null || $fromusername!==null)) {
            $params = ['fromusername'=>$fromusername,'to_id'=>$to_id == null ? null : (int) $to_id];
            $searchModel = new MessageSearch;
            $dataProvider = $searchModel->search($params);
            $_GET['page'] = $page === null ? ceil($dataProvider->query->count()/15) : $page;
            foreach ($dataProvider->getModels() as $key => $value) {
                if ($value->genre == 1) {
                    $array[$key] = $value->storage->toArray();
                } else {
                    $array[$key] = $value->service->toArray();
                }
                $array[$key]['content'] = $value->value;
                $array[$key]['timestamp'] = $value->created_at*1000;
            }
            return isset($array) ? ['page'=>$_GET['page'],'array'=>$array] : [];
        }
        return false;
    }

    /**
     * [online 修改客服状态]
     * @return [type] [description]
     */
    public function actionOnline($status)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (null !== $model = WxServices::findOne(['id'=>Yii::$app->user->id])) {
            $model->status = $model->setChatStatus($status);
            return $model->save();
        }
        return false;
    }
    /**
     * [actionThrough description]
     * @return [type] [description]
     */
    public function actionThrough()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        foreach (WxServices::find()->select(["username","id","identity","status"])
        ->where(['and',['appid'=>Yii::$app->user->identity->appid],['!=','id',Yii::$app->user->id]])->andWhere(['!=','status',WxCustomer::STATUS_OFFLINE])->asArray()->all() as $key => $value) {
            
            $array[$key]['groupname'] = $value['username']."【".$value['identity']."】".Yii::$app->user->identity->userStatus()[$value['status']];
            $array[$key]['id'] = $value['id'];
        }
        return isset($array)?$array:false; 
    }
    /**
     * @inheritdoc
     */
    public function actionAssociate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ([]!== $post = Yii::$app->request->post()) {
            if (false !== $model = WxServices::serviceStatus($post['id'],$post['to_id'])) {
                $post['msgtype'] = 'text';
                $post['content'] = '来自客服('.Yii::$app->user->identity->username.'的转接请求)<a href="javascript:void(0);"><span layim-event="chatLog" data-url="'.Url::to(['record','fromusername'=>$post['fromusername'],'to_id'=>Yii::$app->user->id]).'" style="color:red">查看消息记录</span></a>';
                $model->reception_num -=1;
                if($model->save()){
                    return $model->webSocketHttp($post,false);
                }
                return ['errcode'=>404,'errmsg'=>current($model->getErrors())]; 
            }
            return ['errcode'=>404,'errmsg'=>'转接失败 , 操作异常！']; 
        }
        return ['errcode'=>500,'errmsg'=>'转接失败 , 接收参数不能为空！'];
    }
    
    /**
     * [actionFinish description]
     * @return [type] [description]
     */
    public function actionFinish()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ([]!== $post = Yii::$app->request->post()) {
            if (false !== $model = WxServices::serviceStatus($post['id'],Yii::$app->user->id)) {
                //默认处理
                $model->reception_num -=1;
                Yii::$app->wechat->againLoad($model->appid);
                $review = \backend\modules\kfservice\models\External::pushReview($post['id']);
                if($review!=false && $review!=null) 
                    $model->through($post['fromusername'],sprintf($review,Yii::$app->user->id,time())); 
                if(!isset($post['finish']) || $post['finish']==" " || $post['finish']==null){
                    $model->save();
                    return true;
                }
                $model->through($post['fromusername'],$post['finish']);
                $model->save();
                return true; 
            } 
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function actionMessage()
    {
        $searchModel = new RelevanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('message', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Blacklist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Blacklist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxCustomer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMessageUser($fd)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(null !== $model = Yii::$app->user->identity) {
            $model->closeEvent($model->fd);
            $model->fd = (int) $fd;
            $model->reception_num = WxRelevance::find()->where(['to_id'=>Yii::$app->user->id,'status'=>1])->count();
            $model->status = $model->setChatStatus()===false ? WxCustomer::STATUS_ONLINE : $model->setChatStatus();
            if($model->save()){
                \backend\modules\kfservice\models\ServicesRegister::setRegister(Yii::$app->user->id);
                set_time_limit(0);
                $model->offlineMessage(Yii::$app->user->id);
                return ['errcode'=>0,'errmsg'=>'连接成功','status'=>$model->status]; 
            }
            return ['errcode'=>500,'errmsg'=>'连接出错!'];
        }
        return ['errcode'=>404,'errmsg'=>'用户信息不存在！'];
    }

    /**
     * [actionDelete description]
     * @return [type] [description]
     */
    public function actionLoadData()
    {   
        // set_time_limit(0);
        // ini_set('memory_limit', '3024M');
        // $model = new \backend\modules\kfservice\models\WxServices;
        // $model->batchInsert();
        // $model = new \backend\modules\kfservice\models\WxCustomer;
        // $model->batchInsert();
        // $model = new \backend\modules\kfservice\models\WxRelevance;
        // $model->batchInsert();
        // $model = new \backend\modules\kfservice\models\WxMessage;
        // $model->batchInsert();
        // $model = new \backend\modules\kfservice\models\WxConfig;
        // $model->batchInsert();
        // $model = new \backend\modules\kfservice\models\WxStorage;
        // $model->batchInsert();
        return $this->redirect(['index']);
    }

    /**
     * [actionModal 模态]
     * @return [type] [description]
     */
    public function actionModal()
    {   

        if(Yii::$app->request->isPost){
            
            return $this->offlineMessage();
        
        }else{

            if(Yii::$app->user->identity->auth_key == null){
                
               return $this->redirect('https://kefu.huwaishequ.com/site/auth?authclient=weixin');
            }
            return $this->render('wechat');
        }
    }


    /**
     * [ChatMessage 发送客服消息]
     * @param [type] $event [description]
     */
    public function offlineMessage()
    { 
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(null !== $fromusername = Yii::$app->request->post('fromusername')){
            $model = new WxServices;
            return $model->joinUpUser($fromusername);
        }
        return false;
    }

}

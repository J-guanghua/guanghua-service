<?php

namespace backend\modules\kfservice\models;
use Yii;
class ServicesRegister extends \yii\db\ActiveRecord//\yii\db\ActiveRecord
{
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    // public static function getDb()
    // {
    //     return \Yii::$app->get('db2');
    // }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_services_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['services_id','online_time'], 'required'],
            [['services_id'], 'integer'],
            [['host_ip'],'default','value'=>Yii::$app->request->userIP],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'services_id' => '客服序号',
            'online_time' => '上线时间',
            'offline_time' => '离线时间',
            'host_ip'=>'主机IP'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function setRegister($services_id,$online_time = true)
    {
        if($online_time === true){
            return (new ServicesRegister(['online_time'=>time(),
              'services_id'=>$services_id]))->save();
        } else {
          
          return Yii::$app->db->createCommand()->update(static::tableName(),
            ['offline_time'=>time()],['offline_time'=>null,'services_id'=>$services_id])->execute();  
        }
    }

}
?>
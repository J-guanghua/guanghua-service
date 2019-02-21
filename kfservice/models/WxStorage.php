<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\caching\TagDependency;
use yii\behaviors\TimestampBehavior;
use common\behaviors\ActiveRecordHelper;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxStorage extends \yii\db\ActiveRecord 
{
    const TYPE_WAIT = 'wait';
    const TYPE_SELECT = 'select';
    public static $identity_map = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_storage_mongo';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data','type','fromusername','appid'], 'required'],
            [['created_at','updated_at','to_id'], 'integer'],
            [['fromusername','appid','type'], 'string', 'max' => 32],
            [['data'], 'string', 'max' => 1024],
        ];
    }

     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DataBehavior::className(),
           TimestampBehavior::className(),
        ];
    }

    /**
     * @param int $id
     * @return Form|null
     */
    public static function findIdentity($fromusername = null)
    {
        if (null === $fromusername) {
            return null;
        }
        return static::findOne(['fromusername' => $fromusername,'type'=>WxStorage::TYPE_WAIT]);
    }

}
?>
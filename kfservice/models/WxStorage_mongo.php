<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\caching\TagDependency;
use yii\behaviors\TimestampBehavior;
use common\behaviors\ActiveRecordHelper;
use backend\modules\kfservice\behaviors\DataBehavior;
class WxStorage extends \yii\mongodb\ActiveRecord//\yii\db\ActiveRecord 
{
    const TYPE_WAIT = 'wait';
    const TYPE_SELECT = 'select';
    public static $identity_map = [];
    
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'wx_storage_mongo';
    }
    /**
     * [attributes description]
     * @return [type] [description]
     */
    public function attributes()
    {
        return ['_id','data','id', 'type','fromusername', 'appid','created_at','updated_at','to_id'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data','type','fromusername','appid'], 'required'],
            [['created_at','updated_at','to_id','id'], 'integer'],
            [['fromusername','appid','type'], 'string', 'max' => 32],
            [['data'], 'string', 'max' => 1024],
        ];
    }
    public function transform()
    {
        return ['created_at','id','updated_at','to_id'];
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

<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/24
 * Time: 下午1:26
 */

namespace backend\modules\kfservice\behaviors;

use Yii;  
use yii\base\Behavior;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;
class DataBehavior extends Behavior
{   
    public $service_id = 0;
    /**
     * Get events list.
     * @return array
     */
    // public function events()
    // {
    //     return [
    //         ActiveRecord::EVENT_AFTER_DELETE => 'invalidateDle',
    //         ActiveRecord::EVENT_AFTER_INSERT => 'invalidateData',
    //         ActiveRecord::EVENT_AFTER_UPDATE => 'invaliUpdate',
    //         ActiveRecord::EVENT_BEFORE_INSERT=> 'touchIndex'
    //     ];
    // }
    /**
     * @inheritdoc
     */
    public function conver($str) {
        $str = lcfirst($str);
        $str = preg_replace_callback("/[A-Z]/",function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
        return $str;
    }
    /**
     * @inheritdoc
     */
    public function batchInsert($mongodb = false)
    {
        if($this->owner instanceof \yii\mongodb\ActiveRecord){
            $tableNames = $this->owner->className();
            return $this->batchInsertMongodb($tableNames::collectionName());
        
        } elseif ($this->owner instanceof \yii\db\ActiveRecord) {
            $tableNames = $this->owner->className();
            $keys = [];$array = [];
            foreach ((new \yii\mongodb\Query())->from($tableNames::tableName())->all() as $key => $value) {
                $keys = array_keys($this->owner->attributes);
                if(isset($value['_id'])) unset($value['_id']);
                $array[$key] = array_merge($this->owner->attributes,$value);
            }
            return $this->batchInsertMysql($tableNames::tableName(),$keys,$array,null);
        }
        throw new NotFoundHttpException("当前连接不在操作范围！");;
    }
    /**
     * @inheritdoc
     */
    public function batchInsertMongodb($tableName)
    {   
        $array = [];
        foreach ((new \yii\db\Query)->from($tableName)->all() as $key => $value) {
            unset($value['_id']);
            $array[$key] = $value;
            $keys = array_keys($value);
            foreach ($value as $ke => $val) {
                in_array($ke,$this->owner->transform()) ? $array[$key][$ke] = (int) $val : null;
            }
        }
        $collection = Yii::$app->mongodb->getCollection($tableName);
        return $collection->batchInsert($array);
    }

    /**
     * @inheritdoc
     */
    public function batchInsertMysql($tableName,$keys,$tabledata,$suffix='_mongo')
    {   
        $db = Yii::$app->db;
        $sql = $db->queryBuilder->batchInsert("{$tableName}{$suffix}",$keys, $tabledata);
        if($db->createCommand(str_replace("INSERT INTO ","INSERT IGNORE INTO ",$sql))->execute()){
            return true;
        }
        throw new NotFoundHttpException("{$tableName}数据已存在");
    }
    /**
     * @param callable $callback
     * @throws InvalidConfigException
     */
    public function touchIndex($get = false)
    { 
        if($this->owner->isAttributeActive('id') 
            && $this->owner instanceof \yii\mongodb\ActiveRecord){
            
            $model = $this->owner->className();
            $tableName = $model::collectionName();
            $fileName = __DIR__."/{$tableName}.data";
            $isNew = !file_exists($fileName);
            touch($fileName);
            if (($file = fopen($fileName, 'r+')) === false) {
                return false;
            }
            flock($file, LOCK_EX);
            try {
                if('' === $content = stream_get_contents($file)){
                    $content = $this->owner->find()->max('id');
                };
                $this->owner->id = intval($content) + 1;
                ftruncate($file, 0);
                rewind($file);
                fwrite($file, $this->owner->id);
                fflush($file);
            } finally {
                flock($file, LOCK_UN);
                fclose($file);
            }
            return $content;
        }
        return false;
    }
    /**
     * [invalidateData description]
     * @return [type] [description]
     */
    public function invalidateDle(){
       
       if($this->owner instanceof \yii\mongodb\ActiveRecord){
            $tableName = $this->owner->className();
            return Yii::$app->db->createCommand()->delete($tableName::collectionName(),['id'=>(string) $this->owner->id])->execute();
	   }
    }
    /**
     * [invalidateData description]
     * @return [type] [description]
     */
    public function invalidateData(){
    	if($this->owner instanceof \yii\mongodb\ActiveRecord){
            $array = $this->owner->toArray();
            unset($array['_id']);
            $tableName = $this->owner->className();
            return Yii::$app->db->createCommand()->insert($tableName::collectionName(), $array)->execute();
	   }
    }
    /**
     * [invalidateData description]
     * @return [type] [description]
     */
    public function invaliUpdate(){
	    if($this->owner instanceof \yii\mongodb\ActiveRecord){
            $array = $this->owner->toArray();
            unset($array['_id']);
            unset($array['id']);
            $tableName = $this->owner->className();
	        return Yii::$app->db->createCommand()->update(
                $tableName::collectionName(), $array,['id'=>$this->owner->id])->execute();
	    }
    }
    /**
     * @inheritdoc
     */
    public function getUser()
    { 
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxCustomer::className(), ['id' => 'fo_id']);
    }

    /**
     * @inheritdoc
     */
    public function getService()
    { 
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxServices::className(), ['id' => 'to_id']);
    }

    /**
     * @inheritdoc
     */
    public function getServices()
    { 
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxServices::className(), ['id' => 'services_id']);
    }
    /**
     * @inheritdoc
     */
    public function getStorage()
    { 
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxCustomer::className(), ['fromusername' => 'fromusername']);
    }
    /**
     * @inheritdoc
     */
    public function getStorages()
    { 
        return $this->owner->hasMany(\backend\modules\kfservice\models\WxStorage::className(), ['fromusername' => 'fromusername']);
    }
    /**
     * @inheritdoc
     */
    public function getRelevances()
    { 
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxRelevance::className(), ['fo_id' => 'id'])->where(['to_id'=>$this->service_id]);
    }
    /**
     * @inheritdoc
     */
    public function getLinkStatus(){
        
        return $this->owner->hasOne(\backend\modules\kfservice\models\WxRelevance::className(), ['fo_id' => 'id'])->where(['status'=>\backend\modules\kfservice\models\WxRelevance::IDENTITY_ONE]);

    }
}

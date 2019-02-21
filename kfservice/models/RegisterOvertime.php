<?php

namespace backend\modules\kfservice\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\modules\kfservice\behaviors\DataBehavior;
/**
 * This is the model class for table "wx_register_overtime".
 *
 * @property integer $id
 * @property string $fromusername
 * @property integer $services_id
 * @property integer $message_id
 * @property integer $overtime
 * @property integer $created_at
 */
class RegisterOvertime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_register_overtime';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fromusername', 'services_id', 'message_id'], 'required'],
            [['services_id', 'message_id', 'overtime', 'created_at'], 'integer'],
            [['fromusername'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fromusername' => 'openID',
            'services_id' => '客服',
            'message_id' => '消息ID',
            'overtime' => '回复间隔时间',
            'created_at' => '创建时间',
        ];
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DataBehavior::className(),
           [
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
           ],
        ];
    }

}

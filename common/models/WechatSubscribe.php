<?php
namespace common\models;


class WechatSubscribe extends BaseModel
{
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%wechat_subscribe}}';
    }

    public function getLinkReqUser()
    {
        return $this->hasOne(User::className(),['id'=>'req_user_id']);
    }
}
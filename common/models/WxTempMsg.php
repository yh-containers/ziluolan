<?php
namespace common\models;
use common\models\use_traits\SoftDelete;

class WxTempMsg extends BaseModel
{

    use SoftDelete;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wx_temp_msg}}';
    }

    public static function tempInfo($temp_id=null)
    {
        $data = [
            //订单提交完成通知
            'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po'=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#173177"],
                'keyword2'  =>  ['value' => '',"color"=>"#173177"],
                'keyword3'  =>  ['value' => '',"color"=>"#ff0000"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],
            //订单发货
            'QdWO7qYVi5KBQTe1uuR5005ZWaWOsuCgAEFJ1yF6ohQ'=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#173177"],
                'keyword2'  =>  ['value' => '',"color"=>"#173177"],
                'keyword3'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword4'  =>  ['value' => '',"color"=>"#173177"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],
            //申请
            '6A79z-ZK7PEwEOt8_uIRAPxo_0OMQNsGQrGkywl3-TM'=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword2'  =>  ['value' => '',"color"=>"#173177"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],
        ];
        if(is_null($temp_id)){
            return $data;
        }else{
            $info = isset($data[$temp_id])?$data[$temp_id]:[];
            return $info;
        }
    }

    public static function sendMessage($user_id,$temp_id,array $data,$url='')
    {
        try{
            $content = self::tempInfo($temp_id);
            if(empty($content)) throw new \Exception('请检测模版id');
            foreach($content as $key=>&$vo){
                if(isset($data[$key])){
                    $vo['value'] = $data[$key];
                }
            }

            //获取订单用户信息
            $user_info = \common\models\User::findOne($user_id);
            //先入库
            $model = new self();
            $model->temp_id= $temp_id;
            $model->uid= $user_id;
            $model->openid= $user_info['open_id'];
            $model->data= json_encode($data);
            $model->content= json_encode($content,JSON_UNESCAPED_UNICODE);
            $model->save(false);
            if($user_info['open_id']){
                $wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
                $wx_object->sendTemp($user_info['open_id'],$temp_id,$content,$url);
            }
        }catch (\Exception $e){
            \Yii::info('send error:'.$e->getMessage().'::error line:'.$e->getLine(), __METHOD__);
        }

    }

}

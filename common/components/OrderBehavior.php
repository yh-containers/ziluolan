<?php
namespace common\components;

use common\models\Order;
use common\models\SysManager;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class OrderBehavior extends Behavior
{
    // 其它代码

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'handleLogs',
            ActiveRecord::EVENT_AFTER_UPDATE => 'handleLogs',
        ];
    }

    public function handleLogs($event)
    {

        $changedAttributes = empty($event->changedAttributes)?[]:$event->changedAttributes;
        if(!empty($event->sender)){
            $object = $event->sender;

            // 处理器方法逻辑
            if($event->name==ActiveRecord::EVENT_AFTER_INSERT){



            }elseif ($event->name==ActiveRecord::EVENT_BEFORE_DELETE){

            }elseif ($event->name==ActiveRecord::EVENT_AFTER_UPDATE){
                //更新
                if(array_key_exists('status',$changedAttributes)){
                    if($object->status==1){
                        //签收完成
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
                            [
                                'first' => '付款成功',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $object->getAttribute('create_time')?date('Y-m-d H:i:s',$object->getAttribute('create_time')):'',
                                'keyword3' => $object->getAttribute('pay_money'),
                                'remark'   => '您已付款',
                            ],
                            \yii\helpers\Url::to(['/order/detail','id'=>$object->id],true)
                        );
                    }
                }


//                    if(array_key_exists('is_receive',$changedAttributes)){
//                        if($object->is_receive==2){
//                            //签收完成
//                            \common\models\WxTempMsg::sendMessage($object->uid,
//                                'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
//                                [
//                                    'first' => '已签收',
//                                    'keyword1' => $object->getAttribute('no'),
//                                    'keyword2' => $object->getAttribute('receive_end_time')?date('Y-m-d H:i:s',$object->getAttribute('receive_end_time')):'',
//                                    'keyword3' => $object->getAttribute('pay_money'),
//                                    'remark'   => '订单已签收',
//                                ],
//                                \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
//                            );
//                        }
//                    }



                if(array_key_exists('is_send',$changedAttributes)){

                    if($object->is_send==1){
                        //查询订单物流信息
                        $logistics_info = $object->linkLogistics;
                        //已完成发货
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'QdWO7qYVi5KBQTe1uuR5005ZWaWOsuCgAEFJ1yF6ohQ',
                            [
                                'first' => '您的订单已发货',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $logistics_info['company'],
                                'keyword3' => $logistics_info['no'],
                                'keyword4' => $object->getAttribute('send_end_time')?date('Y-m-d H:i:s',$object->getAttribute('send_end_time')):'',
                                'remark'   => '订单已发货,请耐心等待',
                            ],
                            \yii\helpers\Url::to(['/order/detail','id'=>$object->id],true)
                        );
                    }

                }

            }
        }

    }
}
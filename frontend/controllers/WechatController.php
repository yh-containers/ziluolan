<?php
namespace frontend\controllers;


class WechatController extends CommonController
{
    //无需授权登录
    public $is_need_login=false;

    public function actionIndex()
    {
        if($this->request->isGet){
            //验证流程
            return $this->request->get('echostr');
        }
        $php_input = file_get_contents('php://input');
        $data = $this->_xmlToArray($php_input);
//        dump($data);exit;
//        cache('xml_test_input',$data);
        if(!empty($data) && is_array($data)){
            //消息类型
            $type = $data['MsgType'];
            $return_data = '';
            switch ($type){
                case 'event':
                    $return_data = $this->handleEvent($data);
                    break;
                case 'text':
                    $return_data = $this->handleText($data);
                    break;
                default:
                    break;
            }
//            trace($return_data,'msg_type'.$type);
            return $return_data?$return_data:'success';
        }
//        $response_str = $this->handleResponse($data['FromUserName'],$data['ToUserName'],$data['Content']);

        return 'success';
    }

    //处理事件通知
    public function handleEvent($data)
    {
        //事件类型
        $event = strtolower($data['Event']);
        //subscribe-用户未关注时，进行关注后的事件推送
        //scan-用户已关注时的事件推送
        if($event=='subscribe' || $event == 'scan'){
            //关注/取消关注
            if(isset($data['EventKey'])){
                if(is_string($data['EventKey'])){
                    //二维码扫描关注--获取二维码值
                    $req_user_id = str_replace('qrscene_','',$data['EventKey']);
                    $model_sub = (new \common\models\WechatSubscribe());
                    $model_sub->openid = $data['FromUserName'];
                    $model_sub->req_user_id = $req_user_id;
                    $model_sub->event = $event;
                    $model_sub->create_time = date('Y-m-d H:i:s');
                    $model_sub->save(false);
                }

                if($event=='subscribe'){
                    $wechat_setting = \common\models\SysSetting::getContent('wechat_setting');
                    $wechat_setting = $wechat_setting?json_decode($wechat_setting,true):[];
                    $content = isset($wechat_setting['follow'])?$wechat_setting['follow']:'';
                }

                return !empty($content)?$this->handleResponse($data['ToUserName'],$data['FromUserName'], $content):'';

            }

        }elseif($event=='LOCATION'){
            //上传地址
        }elseif($event=='CLICK'){
            //获取菜单时间
            $model = new \common\models\SysSetting();
            $var_menu = $model->getContent('wechat_menu');
//            trace($var_menu,'aaaaaaaaaaa');
//            trace($data['EventKey'],'bbbbb');
            $var_menu = $var_menu?json_decode($var_menu,true):[];
            $content ='';
            foreach($var_menu as $vo) {
                if(!empty($vo['type']) && !empty($vo['key']) && $vo['key']==$data['EventKey']){
                    if(isset($vo['mod']) && isset($vo['media_id']) && $vo['mod']==2){
                        //图片
                        $content = $this->handleResponse($data['ToUserName'],$data['FromUserName'], $vo['media_id'],'image');
                    }else{
                        $content = $this->handleResponse($data['ToUserName'],$data['FromUserName'], $vo['text']);
                    }
                    break;
                }
                foreach ($vo['sub_button'] as $item){
                    if(!empty($item['key']) && $item['key']==$data['EventKey']){
                        if(isset($item['mod']) && isset($item['media_id']) && $item['mod']==2){
                            //图片
                            $content = $this->handleResponse($data['ToUserName'],$data['FromUserName'], $item['media_id'],'image');
                        }else{
                            $content = $this->handleResponse($data['ToUserName'],$data['FromUserName'], $item['text']);
                        }

                        break;
                    }
                }
            }
            //自定义菜单事件
            return $content;
        }
    }

    //处理文本事件
    public function handleText($data)
    {
        return $this->handleResponse($data['ToUserName'],$data['FromUserName'],$data['Content']);
    }

    /*
     * 消息
     * @param $FromUserName string 发送者
     * @param $ToUserName string 接收者
     * @param $type string 消息类型
     * return array
     * */
    private function handleResponse($FromUserName,$ToUserName,$content,$type='text')
    {
        $time = time();
        if($type=='text'){
            $str = <<<EOT
<xml><ToUserName><![CDATA[$ToUserName]]></ToUserName><FromUserName><![CDATA[$FromUserName]]></FromUserName><CreateTime>$time</CreateTime><MsgType><![CDATA[$type]]></MsgType><Content><![CDATA[$content]]></Content></xml>
EOT;

        }elseif($type=='image'){
            $str = <<<EOT
<xml><ToUserName><![CDATA[$ToUserName]]></ToUserName><FromUserName><![CDATA[$FromUserName]]></FromUserName><CreateTime>$time</CreateTime><MsgType><![CDATA[$type]]></MsgType><Image><MediaId><![CDATA[$content]]></MediaId></Image></xml>
EOT;
        }
        return $str;
    }


    //将XML转为array
    private function _xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
}
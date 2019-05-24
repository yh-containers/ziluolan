<?php
namespace common\components;

use yii\base\BaseObject;

class Wechat extends BaseObject
{
    const CACHE_INDEX = 3;
    const WX_AUTH_USER_INFO = 'WX_AUTH_USER_INFO';
    //购买成功
    const WX_TEMP_MSG_BUY_SUCCESS = 'pBm8xgHayMBv1DPtBFBzto99RY--QlonVdGeqyt28tQ';
    //物流
    const WX_TEMP_MSG_SEND = 'PxQ2je_LAFE_v5iMGk7CCMbUQ28rMR7kDncjGvIzP3c';
    ////提成提醒
    const WX_TEMP_MSG_COMMISSION = 'SOlaoOTYcKIvmuWBnNDz2ueEH8ergh7G82iwPJUyfa4';
    ////转账提醒
    const WX_TEMP_MSG_TRANSFER = '2wB0CYm_phw1DVIkCkzBf4D8hfN9OsxMm6l19G5fu8M';

    //开发者id
    public $appid;
    //开发者密码
    public $appsecret;

    //缓存信息
    const WX_ACCESS_TOKEN_CACHE_NAME = 'WX_ACCESS_TOKEN_CACHE_NAME';

    //获取微信授权登录信息
    public function getAuthInfo($code)
    {
        //换取微信信息
        $param = [
            'appid' => $this->appid,
            'secret' => $this->appsecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/sns/oauth2/access_token',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('授权信息异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('授权异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取用户基本信息
    public function getUserInfo($access_token, $openid)
    {
        $param = [
            'access_token' => $access_token,
            'openid' => $openid,
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/sns/userinfo',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获取用户信息异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取凭证
    public function getAccessToken()
    {
        $access_token = \Yii::$app->cache->get(self::WX_ACCESS_TOKEN_CACHE_NAME);
        if( empty($access_token) ){
            $param = [
                'grant_type' => 'client_credential',
                'appid' => $this->appid,
                'secret' => $this->appsecret,
            ];
            $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/token',$param);
            $info = json_decode($result,true);
            if(empty($info)){
                throw new \Exception('获取access_token异常');
            }else{
                if(!empty($info['errcode'])){
                    //报错
                    throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
                }else{
                    $access_token = $info['access_token'];
                    \Yii::$app->cache->set(self::WX_ACCESS_TOKEN_CACHE_NAME, $access_token, 6000);
                }
            }
        }
        return $access_token;
    }


    /**
     * 带参二维码
     * @param int $scene_id 场景id值
     * @param string $action_name 二维码模式
     * @throws
     * @return array|bool
     * */
    public  function qrcode($scene_id,$action_name='QR_SCENE')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->getAccessToken();
        //{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
        $data = [
            "action_name"   => $action_name,
            "action_info"   => ["scene"=>[
                "scene_id"=>$scene_id
            ]],
        ];
        $json = json_encode($data);
        $result = \common\components\HttpCurl::req($url,$json,'POST',[
            'Content-Type: application/x-www-form-urlencoded'
        ],true);

        $result = json_decode($result,true);
        if(isset($result['ticket'])){

                return [$result['ticket'],$result['url'],$result['expire_seconds']];

        }else{
            return false;
        }
    }



    //发送模板消息
    public function sendTemp($open_id,$template_id,array $msg_data,$url='',array $miniprogram=[])
    {
        $access_token = $this->getAccessToken();
        $msg_data = self::getTempContent($template_id,$msg_data);
        $data=[
            'touser'  => $open_id,
            'template_id' => $template_id,
            'data' => $msg_data,
        ];

        !empty($url) && $data['url']=$url;
        !empty($miniprogram) && $data['miniprogram']=$miniprogram;

        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,json_encode($data,JSON_UNESCAPED_UNICODE),'POST',[],true);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('发送模板异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }
    //模版内容
    public static function getTempContent($temp_id=null,$data=[])
    {
        $content = self::getTemp($temp_id);
        if(empty($content)) throw new \Exception('请检测模版id');
        foreach($content as $key=>&$vo){
            if(isset($data[$key])){
                $vo['value'] = $data[$key];
            }
        }
        return $content;
    }

    //模版消息内容
    public static function getTemp($temp_id=null)
    {
        $data = [
            //购买成功
            self::WX_TEMP_MSG_BUY_SUCCESS=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#173177"],
                'keyword2'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword3'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword4'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword5'  =>  ['value' => '',"color"=>"#ff0000"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],

            self::WX_TEMP_MSG_SEND=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword2'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword3'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword4'  =>  ['value' => '',"color"=>"#ff0000"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],

            self::WX_TEMP_MSG_COMMISSION=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword2'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword3'  =>  ['value' => '',"color"=>"#ff0000"],
                "remark"    =>  ["value"=> '',"color"=>"#173177"]
            ],
            self::WX_TEMP_MSG_TRANSFER=>[
                'first'     =>  ['value' => '',"color"=>"#173177"],
                'keyword1'  =>  ['value' => '',"color"=>"#ff0000"],
                'keyword2'  =>  ['value' => '',"color"=>"#ff0000"],
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
}
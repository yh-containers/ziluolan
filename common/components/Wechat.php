<?php
namespace common\components;

use common\models\BaseModel;
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
    public $key;
    public $merchant_id;

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

                return [$result['ticket'],$result['url'],isset($result['expire_seconds'])?$result['expire_seconds']:false];

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

    //第三方支付
    public function handleJsApiPay($openId,BaseModel $model)
    {
        $lib_path = \Yii::getAlias('@vendor').'/wechat/';
        require_once $lib_path."/lib/WxPay.Api.php";
        require_once $lib_path."/example/WxPay.JsApiPay.php";
        require_once $lib_path."/example/WxPay.Config.php";

        $tools = new \JsApiPay();
        if($model instanceof \common\models\Order){
            $pay_info = $model->getOrderPayInfo();
        }else{
            throw new \Exception('订单数据异常');
        }
        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        isset($pay_info['body']) &&$input->SetBody($pay_info['body']);
        isset($pay_info['attach']) &&$input->SetAttach($pay_info['attach']);
        isset($pay_info['no']) &&$input->SetOut_trade_no($pay_info['no']);
        isset($pay_info['pay_money']) && $input->SetTotal_fee($pay_info['pay_money']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + (isset($pay_info['expire_time'])?$pay_info['expire_time']:600)));
        isset($pay_info['goods_tag']) && $input->SetGoods_tag($pay_info['goods_tag']);
        $input->SetNotify_url($pay_info['notify_url']);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
//        var_dump($input);exit;
        $config = new \WxPayConfig($this->appid,$this->merchant_id,$this->key,$this->appsecret);
        try{
            $order = \WxPayApi::unifiedOrder($config, $input);
            if(isset($order['return_code']) && $order['return_code']=='FAIL'){
                //失败
                throw new \Exception($order['return_msg']);
            }elseif (isset($order['err_code']) ){
                //失败
                throw new \Exception($order['err_code_des'].'.err_code:'.$order['err_code']);
            }
            $jsApiParameters = $tools->GetJsApiParameters($config,$order);
//            var_dump($jsApiParameters);exit;
            return $jsApiParameters;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
//        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//        var_dump($order);exit;


    }

    //处理微信通知回调
    public function handleNotify()
    {
        $lib_path = \Yii::getAlias('@vendor').'/wechat/';
        require_once $lib_path."/example/WxPay.Config.php";

        $config = new \WxPayConfig($this->appid,$this->merchant_id,$this->key,$this->appsecret);
        $notify = new PayNotifyCallBack($config);
        $notify->Handle($config, false);
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

require_once \Yii::getAlias('@vendor').'/wechat/lib/WxPay.Data.php';
require_once \Yii::getAlias('@vendor').'/wechat/lib/WxPay.Notify.php';
require_once \Yii::getAlias('@vendor').'/wechat/lib/WxPay.Api.php';
class PayNotifyCallBack extends \WxPayNotify
{
    /**
     * @var \WxPayConfig
     * */
    protected $wx_pay_config;
    public function __construct(\WxPayConfig $wx_pay_config)
    {
        $this->wx_pay_config = $wx_pay_config;
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

//        $config = new WxPayConfig();
        $result = \WxPayApi::orderQuery($this->wx_pay_config, $input);
//        Log::DEBUG("query:" . json_encode($result));
        \Yii::info("query:" . json_encode($result),'微信支付回调查询'.__METHOD__);

        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return $result;
        }
        return false;
    }

    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {
        \Yii::info("call back， return xml:" . $xmlData,'微信支付回调查询'.__METHOD__);
//        Log::DEBUG("call back， return xml:" . $xmlData);
        return;
    }

    //重写回调处理函数
    /**
     * @param WxPayNotifyResults $data 回调解释出的参数
     * @param WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data)
            ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $msg = "异常异常";
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
//                Log::ERROR("签名错误...");
                \Yii::info("进行签名验证异常:\"签名错误..." ,'微信支付回调查询'.__METHOD__);
                return false;
            }
        } catch(Exception $e) {
            \Yii::info("进行签名验证异常:" . $e->getMessage(),'微信支付回调查询'.__METHOD__);
//            Log::ERROR(json_encode($e));
        }

        //TODO 3、处理业务逻辑
        \Yii::info("处理业务逻辑:" . json_encode($data),'微信支付回调查询'.__METHOD__);
        $notfiyOutput = array();


        //查询订单，判断订单真实性
        $query_info = $this->Queryorder($data["transaction_id"]);
        if($query_info===false){
            \Yii::info("订单查询失败:" . json_encode($query_info),'微信支付回调查询'.__METHOD__);
        }

        //订单处理
        if($query_info['trade_state']=='SUCCESS'){
            //支付成功
            \common\models\Order::handleNotify($data["out_trade_no"],$data);
        }



        return [true,$query_info['trade_state']];
    }
}


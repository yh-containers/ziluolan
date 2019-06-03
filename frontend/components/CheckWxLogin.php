<?php
namespace frontend\components;

use yii\base\BaseObject;

class CheckWxLogin extends BaseObject
{
    //忽略微信授权登录的控制器
    public $ignore_wx_auth_controller = ['wechat'];

    public function init()
    {
        parent::init();
        //执行微信授权登录
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;
        $session->isActive || $session->open();
        //当前请求地址
        $absoluteUrl = $request->absoluteUrl;

        //是否跳过微信授权验证
        $is_ignore = false;
        foreach ($this->ignore_wx_auth_controller as $vo){
            if(stripos($absoluteUrl,$request->hostInfo.'/'.$vo)!==false){
                $is_ignore = true;
            }
        }
        //验证是否已有登录用户
        if(!$is_ignore && !$session->has(\common\models\User::USER_SESSION_LOGIN_INFO)){
            //验证微信浏览器
//            if ( ($request->isGet && preg_match('~micromessenger~i', $request->userAgent)) ) {

                $wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
                $code = $request->get('code');
                $state = $request->get('state');
                if($code && $state){

                    try{
                        $info = $wx_object->getAuthInfo($code);
                        //获取用户资料
                        $user_info = $wx_object->getUserInfo($info['access_token'],$info['openid']);
                        //设置session信息
                        $session->set(\common\components\Wechat::WX_AUTH_USER_INFO,array_merge($user_info,$info));
                        $model_user = \common\models\User::checkWxLoginInfo();
                        if(!empty($model_user)){
                            //用户登录流程
                            $model_user->handleLogin();
                        }
//                        var_dump($model_user->getAttribute());exit;
                    }catch (\Exception $e){
                        //授权异常
//                    var_dump($e->getMessage());exit;
                    }

                }else{
                    //微信授权登录流程
                    //微信授权链接
                    $auth_link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.
                        $wx_object->appid.'&redirect_uri='.
                        urlencode($absoluteUrl).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
                    \Yii::$app->response->redirect($auth_link)->send();
                }
//            }
        }



    }
}
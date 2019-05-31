<?php
namespace frontend\components;

use yii\base\BaseObject;

class CheckWxLogin extends BaseObject
{
    //微信对象
    public $wx_object;

    public function init()
    {
        parent::init();
        //验证是否已有登录用户
        if(!\Yii::$app->has(\common\models\User::USER_SESSION_LOGIN)){
            //执行微信授权登录
            $request = \Yii::$app->request;
            //验证微信浏览器
            if ( ($request->isGet && preg_match('~micromessenger~i', $request->userAgent)) ) {
                $session = \Yii::$app->session;
                $this->wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
                $code = $request->get('code');
                $state = $request->get('state');
                if($code && $state){

                    try{
                        $info = $this->wx_object->getAuthInfo($code);
                        //获取用户资料
                        $user_info = $this->wx_object->getUserInfo($info['access_token'],$info['openid']);
                        //设置session信息
                        $session->set(\common\components\Wechat::WX_AUTH_USER_INFO,array_merge($user_info,$info));
                        $model_user = \common\models\User::checkWxLoginInfo();
                        if(!empty($model_user)){
                            //用户登录流程
                            $model_user->handleLogin();
                        }


                    }catch (\Exception $e){
                        //授权异常
//                    var_dump($e->getMessage());exit;
                    }


                }else{
                    //微信授权登录流程

                    //当前请求地址
                    $absoluteUrl = $request->absoluteUrl;
                    //微信授权链接
                    $auth_link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.
                        $this->wx_object->appid.'&redirect_uri='.
                        urlencode($absoluteUrl).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
                    \Yii::$app->response->redirect($auth_link);
                }
            }
        }



    }
}
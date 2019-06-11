<?php
namespace backend\controllers;


class IndexController extends CommonController
{
    protected $ignore_action = 'captcha,login,error';

    public function actions()
    {
        return [
            //默认验证码刷新页面不会自动刷新
            'captcha' => [
                'class' => 'backend\components\CaptchaAction',
                'testLimit' => 1,
                'maxLength' => 4,
                'minLength' => 4,
                'padding' => 1,
                'height' => 50,
                'width' => 140,
                'offset' => 1,
            ],
        ];
    }

    /*
     * 操作异常
     * */
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if($exception instanceof \yii\base\UserException){
                //状态码
                \Yii::$app->response->statusCode=200;
                if($this->request->isAjax){
                    return $this->asJson(['code'=>0,'msg'=>$exception->getMessage()]);
                }
            }
            $this->layout='main';
            return $this->render('site/error', ['exception' => $exception,'message'=>$exception->getMessage()]);
        }
    }
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
    public function actionInfo()
    {
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $lang = $this->request->post('lang');
            $model = \common\models\Base::find()->where(['lang'=>$lang])->one();
            if(empty($model)){
                $model = new \common\models\Base();

            }
            //修改
            $model->setAttributes($php_input,false);
            $bool = $model->save(false);
            return $this->asJson(['code'=>(int)$bool,'msg'=>$bool?'操作成功':'操作失败']);
        }
        $model = \common\models\Base::find()->where(['lang'=>1])->one();
        return $this->render('info',[
            'model' => $model,
        ]);
    }


    //登录页面
    public function actionLogin()
    {
        if($this->request->isPost || $this->request->isAjax){
            $account = $this->request->post('account');
            $password = $this->request->post('password');
            $verify = $this->request->post('verify');

            if(empty($account)) throw new \yii\base\UserException('请输入帐号');
            if(empty($password)) throw new \yii\base\UserException('请输入密码');
            if(empty($verify)) throw new \yii\base\UserException('请输入验证码');

            $captcha = new \yii\captcha\CaptchaValidator();
            $captcha->captchaAction = 'index/captcha';
            if(!$captcha->validate($verify))  throw new \yii\base\UserException('验证码错误');

            $manage = \common\models\SysManager::find()->where(['account'=>$account])->one();
            if(empty($manage)) throw new \yii\base\UserException('用户不存在');
            $generate_pwd = \common\models\SysManager::generatePwd($password,$manage->salt);
            if($generate_pwd!=$manage->password) throw new \yii\base\UserException('用户名或密码不正确');
            if($manage->status!=1) throw new \yii\base\UserException('帐号已被禁用');
            //判断是否是超级管理

            $session = \yii::$app->session;
            // 开启session
            $session->open();
            $session->setTimeout(86400);
            $session['user_info'] =[
                'user_id' => $manage->id,
            ];
            return $this->asJson(['code'=>1,'msg'=>'登录成功','url'=>\yii\helpers\Url::to(['index/index'])]);
        }

        return $this->renderPartial('login',[

        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $session = \yii::$app->session;
        $session->destroy();

        return $this->goHome();
    }

}

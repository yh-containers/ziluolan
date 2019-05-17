<?php
namespace frontend\controllers;


class IndexController extends CommonController
{
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
}
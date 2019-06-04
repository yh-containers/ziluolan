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
        //轮播图
        $model_ad = \common\models\Ad::find()->asArray()->where(['status'=>1])->orderBy('sort asc')->all();
        //产品展示
        $model_goods = \common\models\Goods::find()->asArray()->with(['linkSkuAttrPriceOne'])->where(['status'=>1])->orderBy('sort asc')->limit(8)->all();
        //新闻资讯
        $model_news = \common\models\Article::find()->with(['linkNavPage'])->asArray()->where(['status'=>1])->orderBy('addtime desc')->limit(6)->all();
//        var_dump($model_news);exit;
        return $this->render('index',[
            'model_ad' => $model_ad,
            'model_goods' => $model_goods,
            'model_news' => $model_news,
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
    public function actionCacheFlush()
    {
        $session = \yii::$app->session;
        $session->destroy();
        \yii::$app->cache->flush();

        return $this->goHome();
    }
}
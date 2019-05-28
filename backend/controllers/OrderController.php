<?php
namespace backend\controllers;


class OrderController extends CommonController
{

    public function actionIndex()
    {


        //会员模型
        $query = \common\models\Order::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query
            ->with(['linkUser'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy("id desc")
            ->all();

        return $this->render('index',[
            'list'  => $list,
            'pagination' => $pagination,
        ]);
    }

    //订单详情
    public function actionDetail()
    {
        $id = $this->request->get('id');
        $model = \common\models\Order::find()->with(['linkUser','linkGoods','linkAddr','linkLogistics','linkOrderComLog.linkUser'])->where(['id'=>$id])->one();
        //可操作
        $m_handle = [];
        $model && $m_handle = $model->getUserHandleAction('m_handle');
        return $this->render('detail',[
            'model'=>$model,
            'm_handle' => $m_handle
        ]);
    }


    //删除订单
    public function actionDel()
    {

        $id = $this->request->get('id');
        try{
            \common\models\Order::del($this->user_model,$id);
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }



    }

    //取消订单
    public function actionCancel()
    {

        $id = $this->request->get('id');
        try{
            \common\models\Order::cancel($this->user_model,$id);
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }



    }

    //确定支付
    public function actionSurePay()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::surePay($this->user_model,$id);
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }
    }
    //发货
    public function actionSendDown()
    {
        $id = $this->request->get('id');
        $logistics = $this->request->get('logistics');
        try{
            \common\models\Order::optSend($id,$logistics);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }


}

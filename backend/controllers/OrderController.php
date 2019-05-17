<?php
namespace backend\controllers;


class OrderController extends CommonController
{

    //订单详情
    public function actionDetail()
    {
        $id = $this->request->get('id');
        $model = \common\models\OrderList::find()->with(['linkMember','linkRecAddr'])->where(['id'=>$id])->one();
        //获取购物信息
        $cart_id_arr=$model['cart_id']?explode('|',$model['cart_id']):[];
        $cart_info=\common\models\Cart::find()->asArray()->with(['linkProduct'])->where(['id'=>$cart_id_arr])->all();

        return $this->render('detail',[
            'model'=>$model,
            'cart_info'=>$cart_info,
        ]);
    }

    //删除订单
    public function actionDel()
    {
        $id = $this->request->get('id');
        $model = \common\models\OrderList::findOne($id);
        if(empty($model)) throw new \yii\base\UserException('删除对象不存在');
        $bool = $model->delete();
        return $this->asJson(['code'=>(int)$bool,'msg'=>$bool?'删除成功':'删除失败']);

    }

    //确定支付
    public function actionSurePay()
    {
        $id = $this->request->get('id');
        try{
            \common\models\OrderList::surePay($id);
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }
    }
    //发货
    public function actionSend()
    {
        $id = $this->request->post('send_id');
        $php_input = $this->request->post();
        try{
            \common\models\OrderList::send($id,$php_input);
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }
    }

}

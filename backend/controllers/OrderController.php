<?php
namespace backend\controllers;


class OrderController extends CommonController
{

    public function actionIndex()
    {
        $user_id = $this->request->get('user_id');
        $pay_way = $this->request->get('pay_way');
        $admin_id = $this->request->get('admin_id');
        $time_start = $this->request->get('time_start');
        $time_end = $this->request->get('time_end');
        $keyword = $this->request->get('keyword');
        $keyword = trim($keyword);


        //会员模型
        $query = \common\models\Order::find();
        //是否是门店管理员
        if($this->is_store_manager_id!==false){
            $query = $query->andWhere(['admin_id'=>$this->is_store_manager_id]);
        }

        !empty($user_id) &&  $query = $query->andWhere(['uid'=>$user_id]);
        !empty($admin_id) &&  $query = $query->andWhere(['admin_id'=>$admin_id]);
        $pay_way!='' && is_numeric($pay_way) &&  $query = $query->andWhere(['pay_way'=>$pay_way]);
        !empty($keyword) && $query= $query->andWhere(['like','no',$keyword]);
        //按时间查询
        if(!empty($time_end) && !empty($time_start) && $time_end>=$time_start){
            $query = $query->andWhere(['and',['>=','create_time',strtotime($time_start)],['<=','create_time',strtotime($time_end)+86400]]);

        }elseif (!empty($time_start)){
            $query = $query->andWhere(['>=','create_time',strtotime($time_start)]);

        }elseif (!empty($time_end)){
            $query = $query->andWhere(['<=','create_time',strtotime($time_end)]);
        }

        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query
            ->with(['linkUser','linkStore'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy("id desc")
            ->all();
        //今日营业额
        $today_money = \common\models\Order::find()->where(['>','status',0])->andWhere(['>=','create_time',strtotime(date('Y-m-d'))])->sum('money');
        $today_money = sprintf('%.2f',$today_money);

        //获取门店管理员
        $store = \common\models\SysManager::getStoreRole();
        return $this->render('index',[
            'store'  => $store,
            'list'  => $list,
            'time_start'  => $time_start,
            'time_end'  => $time_end,
            'keyword'  => $keyword,
            'pay_way'  => $pay_way,
            'admin_id'  => $admin_id,
            'pagination' => $pagination,
            'today_money' => $today_money,
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



    //导出excel
    public function actionExportExcel()
    {
        $user_id = $this->request->get('user_id');
        $pay_way = $this->request->get('pay_way');
        $admin_id = $this->request->get('admin_id');
        $time_start = $this->request->get('time_start');
        $time_end = $this->request->get('time_end');
        $keyword = $this->request->get('keyword');
        $keyword = trim($keyword);


        //会员模型
        $query = \common\models\Order::find();
        //是否是门店管理员
        if($this->is_store_manager_id!==false){
            $query = $query->andWhere(['admin_id'=>$this->is_store_manager_id]);
        }

        !empty($user_id) &&  $query = $query->andWhere(['uid'=>$user_id]);
        !empty($admin_id) &&  $query = $query->andWhere(['admin_id'=>$admin_id]);
        $pay_way!='' && is_numeric($pay_way) &&  $query = $query->andWhere(['pay_way'=>$pay_way]);
        !empty($keyword) && $query= $query->andWhere(['like','no',$keyword]);
        //按时间查询
        if(!empty($time_end) && !empty($time_start) && $time_end>=$time_start){
            $query = $query->andWhere(['and',['>=','create_time',strtotime($time_start)],['<=','create_time',strtotime($time_end)+86400]]);

        }elseif (!empty($time_start)){
            $query = $query->andWhere(['>=','create_time',strtotime($time_start)]);

        }elseif (!empty($time_end)){
            $query = $query->andWhere(['<=','create_time',strtotime($time_end)]);
        }

        $query = $query
            ->with(['linkUser','linkStore'])
            ->orderBy("id desc");

        $data = [
            ['创建时间','订单号','会员名','会员号','所属门店','订单金额','支付金额','支付方式','发票类型','需求留言','状态'],
        ];
        foreach ($query->batch() as $orders){
            foreach ($orders as $item){
                array_push($data,[
                    $item['create_time']?date('Y-m-d H:i:s',$item['create_time']):'',
                    $item['no'],
                    $item['linkUser']['username'],
                    $item['linkUser']['number'],
                    $item['linkStore']['name'],
                    $item['money'],
                    $item['pay_money'],
                    !is_null($item['pay_way'])?\common\models\Order::getPropInfo('fields_pay_way',$item['pay_way'],'name'):'',
                    \common\models\Order::getPropInfo('fields_invoice',$item['invoice_type'],'name'),
                    $item['remark'],
                    $item->getStepFlowInfo($item['step_flow']),
                ]);
            }
        }

        \backend\components\ExportExcel::handleData($data,'订单信息');
        return $this->render('');

    }

}

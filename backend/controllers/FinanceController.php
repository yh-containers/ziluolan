<?php
namespace backend\controllers;



class FinanceController extends CommonController
{
    //财务列表
    public function actionIndex()
    {
        $spe_money  = $spe_com_money = $spe_fixed_money = $spe_group_money = $spe_freight_money = $spe_in_money = 0.00;
        $money      = $com_money     = $fixed_money     = $group_money     = $freight_money     = $in_money     = 0.00;

        //营业额
        $prefix_order = \common\models\Order::tableName().'.';
        $prefix_order_goods = \common\models\OrderGoods::tableName().'.';
        //获取订单数据
        $data = \common\models\Order::find(true)
            ->asArray()
            ->select([
                'sum_pay_money'=>'sum('.$prefix_order_goods.'pay_money)',//商品金额
                'g_mode',//配送方式
                'freight_money'=>'sum('.$prefix_order_goods.'freight_money*'.$prefix_order_goods.'num)',//商品运费
            ])
            ->joinWith(['linkGoods'],false,'right join')
            ->where(['and',['>',$prefix_order.'step_flow',0],['not in',$prefix_order.'status',[0,2]]])
            ->groupBy($prefix_order_goods.'g_mode') //按商品佣金模式分组
            ->all();
        //获取消费日志
        $com_in = \common\models\UserLog::find()
            ->where(['in','type',[1,2,3]])
            ->all();
        foreach ($com_in as $vo){
            if($vo['type']==3){
                //团队
                $spe_group_money += $vo['quota'];
            }else{
                $extra = json_decode($vo['extra'], true);
                if(!empty($extra['data']) && is_array($extra['data'])){
                    foreach ($extra['data'] as $item){
                        if(isset($item['g_type'])){
                            if($item['g_type']==1){
                                //固定模式
                                $spe_com_money +=$item['get_money'];//
                            }elseif($item['g_type']==2){
                                //推荐模式
                                $spe_fixed_money +=$item['get_money'];//
                            }else{
                                //普通产品
                                $com_money +=$item['get_money'];//
                            }
                        }
                    }
                }
            }
        }

        foreach ($data as $vo){
            if($vo['g_mode']){
                //模式奖
                $spe_money +=$vo['sum_pay_money'];
                $spe_freight_money +=$vo['freight_money'];
            }else{
                //普通产品
                $money +=$vo['sum_pay_money'];
                $freight_money +=$vo['freight_money'];
            }
        }
        //总收入
        $spe_in_money = $spe_money+$spe_freight_money-$spe_com_money-$spe_fixed_money-$spe_group_money;
        $in_money = $money+$freight_money-$com_money-$fixed_money-$group_money;
        return $this->render('index',[
            //模式奖
            'spe_money' => $spe_money,
            'spe_com_money' => $spe_com_money,
            'spe_fixed_money' => $spe_fixed_money,
            'spe_group_money' => $spe_group_money,
            'spe_freight_money' => $spe_freight_money,
            'spe_in_money' => $spe_in_money,

            //普通产品
            'money' => $money,
            'com_money' => $com_money,
            'fixed_money' => $fixed_money,
            'group_money' => $group_money,
            'freight_money' => $freight_money,
            'in_money' => $in_money,
        ]);
    }


}

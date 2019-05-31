<?php
namespace common\models;


use common\models\use_traits\SoftDelete;
use MongoDB\Driver\Manager;

class Order extends BaseModel
{
    use SoftDelete;
    public $check_channel = false;
    public $channel_g_data;//渠道数据
    public $order_num = 0;
    //用户可操作常量
    const U_ORDER_HANDLE_PAY = 'pay';           //订单支付
    const U_ORDER_HANDLE_CANCEL = 'cancel';     //取消订单
    const U_ORDER_HANDLE_DEL = 'del';           //删除订单
    const U_ORDER_HANDLE_SURE_REC = 'receive'; //确认收货

    //管理员操作
    const M_ORDER_HANDLE_SURE_PAY = 'sure-pay';      //确定支付
    const M_ORDER_HANDLE_SEND = 'send';         //发送
    const M_ORDER_HANDLE_DEL = 'del';           //删除
    const M_ORDER_HANDLE_CANCEL = 'cancel';     //取消

    //提成-健康豆与金豆比例
    const COM_DES_PER = 0.9; //健康豆
    const COM_CUS_PER = 0.1; //消费金豆
//    const U_ORDER_HANDLE_WAIT_SEND = 'wait_send'; //等待发货

    //虚拟豆兑换比例
    public static $inv_pear_per = 0.1;
    //收货方式
    public static $fields_rec_mode = [
        ['name'=>'自提'],
        ['name'=>'快递'],
    ];
    //支付方式
    public static $fields_pay_way = [
        ['name'=>'微信'],
        ['name'=>'钱包'],
        ['name'=>'线下'],
    ];
    //订单状态
    public static $fields_status = [
        ['name'=>'待付款','style'=>'wait-pay','u_handle'=>[
                self::U_ORDER_HANDLE_PAY=>['rec_mode'=>1],
                self::U_ORDER_HANDLE_PAY,
                self::U_ORDER_HANDLE_CANCEL,
                self::U_ORDER_HANDLE_DEL
            ],'m_handle'=>[
                self::M_ORDER_HANDLE_DEL,self::M_ORDER_HANDLE_CANCEL,self::M_ORDER_HANDLE_SURE_PAY
            ]
        ],
        ['name'=>'已付款','style'=>'sure-pay'],
        ['name'=>'已取消','style'=>'cancel','u_handle'=>[self::U_ORDER_HANDLE_DEL],'m_handle'=>[self::M_ORDER_HANDLE_DEL]],
        ['name'=>'已完成','style'=>'complete','u_handle'=>[self::U_ORDER_HANDLE_DEL]],
    ];
    //发货状态
    public static $fields_is_send = [
        ['name'=>'待发货','m_handle'=>[
            self::M_ORDER_HANDLE_SEND,
        ]],
        ['name'=>'已发货'],
    ];
    //收货状态
    public static $fields_is_recive = [
        ['name'=>'待收货','u_handle'=>[ self::U_ORDER_HANDLE_SURE_REC ]],
        ['name'=>'已收货货'],
    ];

    //
    public static $fields_invoice = [
        ['name'=>'不开发票'],
        [
            'name'=>'普通发票',
            'tip'  => '<p>所有发票运费由客户自己承担</p>
                    <p>以下发票提示只适用于购买特别定制酒的客户。 </p>
                    <p>鉴于增值税的层层抵扣特征，作为《代销服务协议》中的委托人， </p>
                    <p>您有义务给我们开具增值税专用发票，由于您未履行此义务，故我 </p>
                    <p>们只给您开非代售部分的发票，并且以商业折扣的形式开具，发票 </p>
                    <p>将在货物到达之日起 </p>
                    <p>一个月内采用到付的形式寄出。</p>' ,
        ],
        [
            'name' => '增值发票',
            'tip'  => '<p>所有发票运费由客户自己承担</p>
                    <p>以下发票提示只适用于购买特别定制酒的客户。 </p>
                    <p>鉴于增值税的层层抵扣特征，作为《代销服务协议》中的委托人， </p>
                    <p>您有义务给我们开具增值税专用发票，由于您未履行此义务，故我 </p>
                    <p>们只给您开非代售部分的发票，并且以商业折扣的形式开具，发票 </p>
                    <p>将在货物到达之日起 </p>
                    <p>一个月内采用到付的形式寄出。</p>' ,
            'input' => [
                'name'  =>['name'=>'单位名称',],
                'no'    =>['name'=>'纳税人识别号',],
                'addr'  =>['name'=>'注册地址',],
                'tel'   =>['name'=>'注册电话',],
                'bank'  =>['name'=>'开户银行',],
                'bank_card'=>['name'=>'银行账号',],
            ],
        ],
    ];

    public static function tableName()
    {
        return '{{%order}}';
    }


    /**
     * 检出订单信息
     * @param $user_model User 用户模型
     * @param $id int 商品id
     * @param $sku_id int 商品sku_id
     * @param $num int 购买数量
     * @return  array
     * */
    public function checkOrderInfo(User $user_model,$id,$sku_id,$num=1)
    {
        //商品数据
        $goods_sku_ids = $goods_sku_id = $goods_data =[];
        if($this->check_channel=='cart'){

            //购物车过来
            $cart_info = UserCart::find()->asArray()->where(['uid'=>$user_model->id,'is_checked'=>1])->all();
            foreach($cart_info as $vo) {
                if(array_key_exists($vo['sid'],$goods_sku_id)){
                    $goods_sku_id[$vo['sid']] +=$vo['num'];
                }else{
                    $goods_sku_id[$vo['sid']] =$vo['num'];
                }
            }
        }
        if($this->check_channel=='once_again'){
//            $channel_g_data = array_filter(explode(',',$this->channel_g_data));
//            foreach ($channel_g_data as $vo){
//                $arr = explode('-',$vo);
//                if(count($arr)==2){
//                    $gid[$arr[0]] = $arr[1];
//                }
//            }
        }else{
            //指定商品
//            $goods_ids[] = $id;
            $goods_sku_id[$sku_id] = $num;
        }
        //所有商品数据
        $goods_sku_ids = array_keys($goods_sku_id);
        //商品数据
        $goods_sku_price_info = GoodsSkuAttrPrice::find()->asArray()->with(['linkGoods'])->where(['id'=>$goods_sku_ids])->all();
        //获取商品所有规格信息
        $sku_group_ids = array_filter(array_column($goods_sku_price_info,'sku_group'));
        $sku_group_ids = str_replace('|',',',implode(',',$sku_group_ids));
        $sku_group_ids_where = $sku_group_ids?array_unique(explode(',',$sku_group_ids)):[];
        //获取属性信息
        $sku_attr = GoodsSkuAttr::find()->asArray()->with(['linkSku'])->where(['id'=>$sku_group_ids_where])->all();
        !empty($sku_attr) && $sku_attr = array_column($sku_attr,null,'id');

        foreach ($goods_sku_price_info as $vo){
            if(empty($vo['linkGoods'])){
                continue;
            }

            if(isset($goods_sku_id[$vo['id']])){
                $vo['sku_group'] = explode('|',$vo['sku_group']);
                $vo['sku_group_name'] = '';
                $vo['sku_group_info'] = [];
                foreach($vo['sku_group'] as $sku_id){
                    if(isset($sku_attr[$sku_id])){
                        $vo['sku_group_info'][]=$sku_attr[$sku_id];
                        $vo['sku_group_name'].=$sku_attr[$sku_id]['name'];
                    }
                }
                //商品数据
                $goods_arr = $vo;
                //商品信息
                $goods_arr['goods_info'] = $vo['linkGoods'];
                $goods_arr['buy_num'] = $goods_sku_id[$vo['id']];
                $goods_data[]=  $goods_arr;
            }
        }
        //计算金额相关数据
        $money = [
            'money' => 0.00 ,//总金额
            'goods_money' => 0.00 ,//商品总金额
            'pay_money' => 0.00 ,//实际支付总金额
            'total_money_no_freight' => 0.00 ,//不含运费总额
            'freight_money' => 0.00 ,//运费金额
            'taxation_money' => 0.00 ,//税费总金额
        ];
        foreach ($goods_data as $vo){

            $goods_price = $vo['price']*$vo['buy_num']; // 购买金额
            $freight_money = 0.00;//$vo['freight_money']*$vo['buy_num']; // 运费金额
            $taxation_money = 0.00;////$vo['taxation_money']*$vo['buy_num']; // 税费金额

            $money['money'] += $goods_price+$freight_money+$taxation_money;
            $money['total_money_no_freight'] += $goods_price+$taxation_money;
            $money['goods_money'] += $goods_price;
            $money['pay_money'] += $goods_price+$freight_money+$taxation_money;
            $money['freight_money'] += $freight_money;
            $money['taxation_money'] += $taxation_money;
        }
        //强转2位小数
        foreach ($money as &$vo){
            $vo = sprintf('%.2f',$vo);
        }

        return [$goods_data,$money];
    }


    /**
     * 确认订单
     * @param User $model_user  当前操作用户
     * @param array $goods_info  购买的商品
     * @Param array $money array 商品金额汇总
     * @Param array $model_addr  购买地址
     * @param array $input_data  用户请求数据
     * @throws
     * @return void
     * */
    public function confirm(User $model_user,$goods_info,$money,$model_addr,array $input_data =[])
    {
        if(empty($model_addr)) throw new \Exception('请选择收货地址');
        if(empty($goods_info)) throw new \Exception('请选择购买商品');
        //收货方式
        $recive_mode = isset($input_data['recive_mode'])?$input_data['recive_mode']:1;
        //备注
        $remark = empty($input_data['message'])?'':trim($input_data['message']);
        //发票
        $invoice_type = isset($input_data['fapiao'])?$input_data['fapiao']:0;
        //虚拟豆数量
        $inv_pear = isset($input_data['inv_pear'])?$input_data['inv_pear']:0;
        //虚拟豆抵扣金额
        $inv_pear_per = self::getPropInfo('inv_pear_per');
        $inv_pear_dis_money = is_numeric($inv_pear_per)?$inv_pear_per*$inv_pear:0.00;
        //发票数据
        $invoice_content = isset($input_data['invoice'])?$input_data['invoice']:'';
        $invoice_data = [];//发票数据
        //发票模版
        $invoice_temp = self::getPropInfo('fields_invoice',$invoice_type,'input');
        if(!empty($invoice_temp) && is_array($invoice_temp) && is_array($invoice_content)){
            foreach ($invoice_content as $key=>$vo){
                $invoice_data[] =[
                    'key'    => $key,
                    'name'   => isset($invoice_temp[$key])?$invoice_temp[$key]['name']:'',
                    'value'  =>  $vo,
                ];
            }
        }
        //订单数据
        $model_order = $this;
        $model_order->no = self::getOrderNo();
        $model_order->uid = $model_user->id;
        $model_order->rec_mode = $recive_mode;
        $model_order->remark = $remark;
        $model_order->invoice_type = $invoice_type;
        //发票数据
        !empty($invoice_data) && $model_order->invoice_content = json_encode($invoice_data);

        if(empty($recive_mode)){
            //自提
            $model_order->money = !empty($money['total_money_no_freight'])?$money['total_money_no_freight']:0.00;
        }else{
            //快递
            $model_order->money = !empty($money['money'])?$money['money']:0.00;
        }

        //使用虚拟豆
        $model_order->use_inv_pear = $inv_pear;
        $model_order->inv_pear_dis_money = $inv_pear_dis_money;

        //优惠金额
        $model_order->dis_money = $inv_pear_dis_money; //总优惠金额
        $model_order->pay_money = $model_order->money-$model_order->dis_money; //实际支付金额
        $model_order->need_pay_money = $model_order->pay_money + $model_order->inv_pear_dis_money; //实际支付金额+消费豆需要金额
        $model_order->freight_money = !empty($money['freight_money'])?$money['freight_money']:0.00;
        $model_order->taxation_money = !empty($money['taxation_money'])?$money['taxation_money']:0.00;

        if($model_order->pay_money<0) throw new \Exception('订单支付金额异常');


        try{
            $transaction = self::getDb()->beginTransaction();
            if($this->check_channel=='cart'){
                //购物车过来删除购物车内容
                UserCart::deleteAll(['uid'=>$model_user->id,'is_checked'=>1]);
            }

            //保存订单信息
            $model_order->save(false);
            //保存收货地址
            $model_order_addr = new OrderAddr();
            $model_order_addr->oid=$model_order->id;
            $model_order_addr->phone=!empty($model_addr['phone'])?$model_addr['phone']:'';
            $model_order_addr->username=!empty($model_addr['username'])?$model_addr['username']:'';
            $model_order_addr->addr=!empty($model_addr['addr'])?$model_addr['addr']:'';
            $model_order_addr->addr_extra=!empty($model_addr['addr_extra'])?$model_addr['addr_extra']:'';
            $model_order_addr->save(false);

            //商品数据
            foreach($goods_info as $vo){
                $model_order_goods = new OrderGoods();
                $model_order_goods->oid = $model_order->id;
                $model_order_goods->gid = $vo['gid'];
                $model_order_goods->g_mode = $vo['linkGoods']['mode'];//分佣模式
                $model_order_goods->sku_id = $vo['id'];
                $model_order_goods->price = $vo['price'];
                $model_order_goods->pay_price = $vo['price']; //商品实际支付金额
                $model_order_goods->num = $vo['buy_num'];
                $model_order_goods->name = $vo['linkGoods']['name'];
                $model_order_goods->sku_name = $vo['sku_group_name'];
                $model_order_goods->sku_attr = json_encode($vo['sku_group_info']);
                $model_order_goods->img = \common\models\Goods::getCoverImg($vo['linkGoods']['image']);
                $model_order_goods->extra = json_encode($vo);//保存商品原始数据
                $model_order_goods->save(false);
            }

            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }



    //订单号
    public static function getOrderNo()
    {
        $cache = \Yii::$app->cache;
        $cache_name = 'order_no'.date('Y-m-d');
        $number = $cache->get($cache_name);
        empty($number) && $number = 0;
        $number=$number+1;
        //保存一天时间
        $cache->set($cache_name,$number,86400);
        $number = sprintf('%05d',$number);
        return date('YmdHis').rand(10,99).$number;
    }

    /**
     * 订单流程
     * @param int|null $step 流程步骤
     * @param string|null $need_field 需要字段
     * @param string $def_attr 流程对应字段属性
     * @return array|string|
     * */
    public function getStepFlowInfo($step=null, $need_field='name', $def_attr='prop_func')
    {
        $data = [
            ['name'=>'支付流程','prop_func'=>'fields_status','field'=>'status'],
            ['name'=>'发货流程','prop_func'=>'fields_is_send','field'=>'is_send'],
            ['name'=>'收货流程','prop_func'=>'fields_is_recive','field'=>'is_receive'],
            ['name'=>'交易已完成','prop_func'=>'fields_status','field'=>'status'],
        ];

        if(is_null($step)){
            return $data;
        }else{
            if(!isset($data[$step])){
                return;
            }
            //流程信息
            $info = $data[$step];
            if($def_attr=='prop_func'){
                return self::getPropInfo($info[$def_attr],$this->getAttribute($info['field']),$need_field);

            }else{
                return is_null($def_attr)?$info:isset($info[$need_field])?$info[$need_field]:'';
            }
        }
    }

    /**
     * 删除订单
     * @param BaseModel $user_model 用户|管理员模型
     * @param int $id 订单id
     * @throws
     * */
    public static function del(BaseModel $user_model,$id)
    {
        if(empty($id) || !is_numeric($id) || $id<=0) throw new \Exception('订单信息异常:id');
        if(empty($user_model)) throw new \Exception('用户资料异常');

        $model = self::findOne($id);
        if($user_model instanceof SysManager){
            //管理员
            $handle_action = $model->getUserHandleAction('m_handle');
            if(!in_array(self::M_ORDER_HANDLE_DEL,$handle_action))  throw new \Exception('订单状态未处于可删除状态');
            $model->m_id_opt_del = $user_model->getAttribute('id');

        }else{
            if(empty($model) || $model['uid']!=$user_model->id)  throw new \Exception('订单数据异常');
            $handle_action = $model->getUserHandleAction();
            if(!in_array(self::U_ORDER_HANDLE_DEL,$handle_action))  throw new \Exception('订单状态未处于可删除状态');
        }


        $model->delete();
        $is_delete = $model->getAttribute(self::getSoftDeleteField());
        if(!$is_delete) throw new \Exception('删除失败');
    }

    /**
     * 取消订单
     * @param User $user_model 用户模型
     * @param $id int 订单id
     * @param $is_force bool 是否强制取消订单
     * @throws
     * */
    public static function cancel(BaseModel $user_model,$id,$is_force=false)
    {
        if(empty($id) || !is_numeric($id) || $id<=0) throw new \Exception('订单信息异常:id');
        if(empty($user_model)) throw new \Exception('用户资料异常');

        $model = self::findOne($id);
        if($user_model instanceof SysManager){
            //管理员
            $handle_action = $model->getUserHandleAction('m_handle');
            if(!in_array(self::M_ORDER_HANDLE_CANCEL,$handle_action))  throw new \Exception('订单状态未处于可删除状态');
            $model->m_id_opt_cancel = $user_model->getAttribute('id');
        }else{
            if(empty($model) || $model['uid']!=$user_model->id)  throw new \Exception('订单数据异常');
            $handle_action = $model->getUserHandleAction();
            if(!in_array(self::U_ORDER_HANDLE_CANCEL,$handle_action))  throw new \Exception('订单状态未处于取消状态');
        }


        $model->status = 2;
        $model->cancel_time = time();
        $save_bool = $model->save(false);
        if(!$save_bool){
            throw new \Exception('订单保存异常');
        }
    }


    /**
     * 收货--订单
     * @param User $user_model 用户模型
     * @param $id int 订单id
     * @param $is_force bool 是否强制取消订单
     * @throws
     * */
    public static function receive(User $user_model,$id)
    {

        if(empty($id) || !is_numeric($id) || $id<=0) throw new \Exception('订单信息异常:id');
        if(empty($user_model)) throw new \Exception('用户资料异常');

        $model = self::findOne($id);
        if(empty($model) || $model['uid']!=$user_model->id)  throw new \Exception('订单数据异常');
        $handle_action = $model->getUserHandleAction();
        if(!in_array(self::U_ORDER_HANDLE_SURE_REC,$handle_action))  throw new \Exception('订单状态未处于待收货状态');


        //确认收货
        $model->step_flow = 3; //完成订单流程
        $model->is_receive=1;//收货成功
        $model->receive_end_time = time();
        //交易完成
        $model->status=3;
        $model->complete_time = time();//交易完成

        $save_bool = $model->save(false);
        if(!$save_bool){
            throw new \Exception('订单保存异常');
        }
    }

    //发货
    public static function optSend($id,array $logistics=[])
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');

        if(empty($logistics['no']))  throw new \Exception('请输入物流单号');
        if(empty($logistics['company']))  throw new \Exception('请输入公司名称');
        $transaction = self::getDb()->beginTransaction();
        try{
            //购买者信息
            $model_user_buy = User::findOne($model['uid']);
            //修改发货状态
            $model->is_send = 1;

            //物流
            $model_logistics = OrderLogistics::find()->where(['oid'=>$id])->limit(1)->one();
            if(empty($model_logistics)){
                $model_logistics = new OrderLogistics();
            }
            $model_logistics->oid = $id;
            $model_logistics->no = $logistics['no'];
            $model_logistics->company = $logistics['company'];
            $model_logistics->money = empty($logistics['money'])?0.00:$logistics['money'];
            $model_logistics->save(false);
            //发货完成
            $model->send_end_time = time();
            $model->step_flow = 2; //进入发货
            $model->is_receive=0;//等待收货状态
            $model->receive_start_time=time();//开始收货时间

            $model->save(false);

            //提成计算
            list($com_money, $group_money) = $model->_commission_cal($model_user_buy);

            //发放佣金--推荐/固定
            if(!empty($com_money)){
                foreach ($com_money as $uid=>$vo){
                    $model_user = User::findOne($uid);
                    $com_money_cal = empty($vo['money'])?0:$vo['money'];
                    if(!empty($model_user)){
                        //获得健康豆
                        $dep_money = intval($com_money_cal*self::COM_DES_PER*100)/100;
                        $model_user->handleDepositMoney($dep_money,$model->getAttribute('id'),'用户消费获得健康豆:'.$dep_money,$vo,1);
                        //获得消费豆
                        $com_money = intval($com_money_cal*self::COM_CUS_PER*100)/100;
                        $model_user->handleConsumWallet($com_money,$model->getAttribute('id'),'用户消费获得消费豆:'.$com_money,$vo,1);
                    }

                }
            }

            //发放佣金--团队
            if(!empty($group_money)){
                foreach ($group_money as $uid=>$vo){
                    $model_user = User::findOne($uid);
                    $com_money_cal = empty($vo['money'])?0:$vo['money'];
                    //获得健康豆
                    $model_user->handleDepositMoney($com_money_cal,$model->getAttribute('id'),'有用户消费获得团队提成增加:'.$com_money_cal,$vo,1);
                    if(!empty($model_user)){
                        //获得健康豆
                        $dep_money = intval($com_money_cal*self::COM_DES_PER*100)/100;
                        $model_user->handleDepositMoney($dep_money,$model->getAttribute('id'),'有用户消费获得团队奖励健康豆:'.$dep_money,$vo,1,1);
                        //获得消费豆
                        $com_money = intval($com_money_cal*self::COM_CUS_PER*100)/100;
                        $model_user->handleConsumWallet($com_money,$model->getAttribute('id'),'有用户消费获得团队奖励消费豆:'.$com_money,$vo,1,1);
                    }
                }
            }

            //团队金增加
            $model_user_buy->handleTeamWallet($model->getAttribute('need_pay_money'));
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception('订单操作异常:'.$e->getMessage());
        }

    }

    /**
     * 确认付款
     * @param SysManager $manager 管理员模型
     * @param int $id 操作订单id
     * @throws
     * */
    public static function surePay(SysManager $manager ,$id)
    {
        $model = self::findOne($id);
        $handle_action = $model->getUserHandleAction('m_handle');
        if(!in_array(self::M_ORDER_HANDLE_SURE_PAY,$handle_action))  throw new \Exception('订单状态未处于待收货状态');

        $model->m_id_opt_pay = $manager->getAttribute('id');
        //确认支付
        $model->_sure_pay();

    }

    //订单支付
    private function _sure_pay()
    {
        $this->step_flow = 1; //进入发货流程
        $this->status = 1;
        $this->pay_time = time();
        $this->save(false);
    }

    //提成流程
    private function _commission_cal(User $model_user_buy)
    {

        if(empty($model_user_buy)){
            return;
        }
        //获取我的所有上级信息
        //队伍链
        $model_user_buy_link = $model_user_buy['fl_uid_all']?explode(',',$model_user_buy['fl_uid_all']):[];
//        $model_user_buy_link = ['887','1463','4643','1297','2312','12','14','16','68','369','837'];
        //直推用户id
        $direct_user_id = isset($model_user_buy_link[0])?$model_user_buy_link[0]:0;
        if(empty($direct_user_id)){
            return;
        }

        //直推商品--用户奖金比例
        $direct_push_per = 0.1;

        //固定金
        $fixed = SysSetting::getContent('fixed');
        $fixed = empty($fixed) ? [] : array_filter(explode(',', $fixed));
        //推荐奖比例
        $recommend = SysSetting::getContent('recommend');
        $recommend = empty($recommend) ? [] : array_filter(explode(',', $recommend));

        //获取订单商品
        $order_goods = $this->linkGoods;



        //佣金信息
        $default_com_data = $fixed_com_data  = $recommend_com_data = [
            'money' => 0,
            'num' => 0,
            'data'  => []
        ];

        foreach ($order_goods as $vo) {
            $num = $vo['num'];
            $total_money = $vo['pay_price'] * $vo['num'];//商品实际支付价格
            $com_data = [
                'money' => $vo['pay_price'],//商品实际支付价格
                'total_money' => $total_money,
                'num' => $vo['num'],
                'g_mode' => $vo['g_mode'],//商品模式
            ];

            if($vo['g_mode']==1){
                //固定
                $fixed_com_data['num']  += $num;
                $fixed_com_data['money'] += $total_money;
                array_push($fixed_com_data['data'],$com_data);

            }elseif($vo['g_mode']==2){
               //推荐奖
                $recommend_com_data['num']  += $num;
                $recommend_com_data['money'] += $total_money;
                array_push($recommend_com_data['data'],$com_data);
            }else{
                //普通模式
                $default_com_data['num']  += $num;
                $default_com_data['money'] += $total_money;
                array_push($default_com_data['data'],$com_data);
            }
        }
        //计算佣金
        $com_money = $group_money = [];
        //普通商品默认提成
        if($default_com_data['money']>0){
            $com_money[$direct_user_id] = [
                'money' => $default_com_data['money']*$direct_push_per, //所得佣金
                'type'  => 0,
                'data'  => array_merge(['sty'=>$direct_push_per],$default_com_data),
            ];
        }

        //固定模式
        foreach ($fixed as $key=>$gd){
            if(isset($model_user_buy_link[$key]) && $fixed_com_data['num']>0){
                $fixed_user_id = $model_user_buy_link[$key];
                if(array_key_exists($fixed_user_id, $com_money)) {
                    $com_money[$fixed_user_id]['money'] += $fixed_com_data['num']*$gd;
                    array_push($com_money[$fixed_user_id]['data'],array_merge(['sty'=>$gd],$fixed_com_data));
                }else{
                    $com_money[$fixed_user_id]=[
                        'money'   => $fixed_com_data['num']*$gd,
                        'type'    => 1,
                        'data'    => array_merge(['sty'=>$gd],$fixed_com_data),
                    ];
                }

            }else{
                //没人直接
                break;
            }
        }
        //推荐模式
        foreach ($recommend as $key=>$gd){
            if(isset($model_user_buy_link[$key]) && $recommend_com_data['money']>0){
                $fixed_user_id = $model_user_buy_link[$key];
                if(array_key_exists($fixed_user_id, $com_money)) {
                    $com_money[$fixed_user_id]['money'] += $recommend_com_data['money']*$gd;
                }else{
                    $com_money[$fixed_user_id]=[
                        'money'   => $recommend_com_data['money']*$gd,
                        'type'    => 2,
                        'data'    => array_merge(['sty'=>$gd],$recommend_com_data),
                    ];
                }

            }else{
                //没人直接
                break;
            }
        }

        //团队模式
        $rev_users = array_reverse($model_user_buy_link);
        //获取团队贡献金额
        $group_users = User::find()->asArray()->select('id,team_wallet')->where(['id'=>$model_user_buy_link])->all();
        $group_users = array_column($group_users,null,'id');
        //离我最近的几个用户拿提成
        $link_user_top_money = [];
        foreach ($rev_users  as $vo){
            if(isset($group_users[$vo])){
                $link_user_top_money[$group_users[$vo]['team_wallet']] =  $group_users[$vo];
            }
        }
        krsort($link_user_top_money,SORT_NUMERIC);

        //处理金额key
        $link_user_top_money= array_values($link_user_top_money);
        $group_record_current_index = false;
        $group_record_all_per = []; //记录所有比例
        foreach ($link_user_top_money as $vo){
            list($group_award_index,$group_award_per) = $this->_get_group_per($vo['team_wallet']);
            if($group_award_index===false || $group_award_per<=0 || $group_record_current_index===$group_award_index){
                //直接结束 //没有达到标准 比例小于0、已处理过同比例的数据
                break;
            }
            //记录此次比例
            $group_record_current_index = $group_award_index;
            array_push($group_record_all_per,$group_award_per);
        }

        //最终团队比例
        $group_per = [];
        //计算比例
        !empty($group_record_all_per) && $group_per = $this->_handle_team_per($group_record_all_per);

        //处理提成
        foreach ($link_user_top_money as $key=>$lgm){

            if(isset($group_per[$key])){
                $per = $group_per[$key];
                $group_money[$lgm['id']]=[
                    'money'   => $this->need_pay_money*$per,
                    'data'    => array_merge(['sty'=>$per,'com_money'=>$this->need_pay_money],$lgm),
                ];
            }else{
                break;
            }

        }


        return [$com_money,$group_money];
    }

    /**
     * 获取用户对订单可执行的操作
     * @param string  $mode 操作人员 u_handle|m_handle
     * @return array
     * */
    public function getUserHandleAction($mode = 'u_handle')
    {
        //可操作流程
        $flow_handle = $this->getStepFlowInfo($this->getAttribute('step_flow'),$mode);
        $handle = [];
        if(!empty($flow_handle) && is_array($flow_handle)){
            foreach ($flow_handle as $index=>$fh){
                if(is_array($fh)){
                    //条件
                    foreach ($fh as $cond_key=>$cond_val){
                        if($this->hasAttribute($cond_key) && $this->getAttribute($cond_key)==$cond_val){
                            array_push($handle,$index);
                        }
                    }
                }else{
                    is_string($fh) && array_push($handle,$fh);
                }
            }
        }
        return $handle;
    }

    /**
     * 计算团队比例
     * */
    private function _get_group_per($team_wallet)
    {
        //团队奖条件倍数
        $cond_multiple = 10000;

        //团队奖
        $group_award = SysSetting::getContent('group_award');
        $group_award = empty($group_award) ? [] : array_filter(explode(',', $group_award));
        $group_award_setting = SysSetting::$_GROUP_AWARD;

        $team_group_index = false;
        $team_group_per = false;
        foreach ($group_award_setting as $cond_key=>$vo){
            $cond = $vo['cond']; //比例

            //是否有设置值
            if(isset($group_award[$cond_key])){
                //查看金额范围
                if(is_array($cond)){
                    if(count($cond)==2){
                        if($team_wallet>=$cond[0]*$cond_multiple && $team_wallet<$cond[1]*$cond_multiple){
                            $team_group_index = $cond_key;
                            $team_group_per = $group_award[$cond_key];
                            break;
                        }
                    }elseif(count($cond)==1){
                        if($team_wallet >= $cond*$cond_multiple){
                            $team_group_index = $cond_key;
                            $team_group_per = $group_award[$cond_key];
                            break;
                        }
                    }

                }
            }
        }

        return [$team_group_index,$team_group_per];
    }

    //处理比例问题
    private function _handle_team_per(array $team_per)
    {
        //处理比例
        $per = [];//最终提成比例
        $cup_per = false;
        $max_per = max($team_per);
        $default_dec_step = 0;//递减比例步进值
        foreach ($team_per as $key=>$vo){
            if($cup_per ===false){
                $i=1;
                for(;;){
                    $cup_per = $i*$vo;
                    //获取精度
                    if($cup_per>1){ break; }
                    //最大执行次数
                    if($i>10000000){ break; }
                    $i*=10;
                }
                $default_dec_step = 1/$i;
            }
            if($default_dec_step>0){
                if(isset($team_per[$key+1])){
                    array_push($per,$default_dec_step);
                    $max_per-=$default_dec_step;//递减
                }else{
                    //最后一个元素
                    array_push($per,$max_per);
                }
            }
        }
        return $per;
    }

    //订单地址
    public function getLinkUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
    //订单地址
    public function getLinkAddr()
    {
        return $this->hasOne(OrderAddr::className(),['oid'=>'id'])->limit(1)->orderBy('id desc');
    }

    //订单商品
    public function getLinkGoods()
    {
        return $this->hasMany(OrderGoods::className(),['oid'=>'id']);
    }

    //订单物流
    public function getLinkLogistics()
    {
        return $this->hasOne(OrderLogistics::className(),['oid'=>'id']);
    }

    //订单提成日志
    public function getLinkOrderComLog()
    {
        return $this->hasMany(UserLog::className(),['cond'=>'id'])->where(['origin_type'=>1]);
    }

}
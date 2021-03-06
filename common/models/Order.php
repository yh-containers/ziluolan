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
        ['name'=>'微信支付'],
        ['name'=>'线下支付'],
        ['name'=>'钱包支付'],
    ];
    //订单状态
    public static $fields_status = [
        ['name'=>'待付款','style'=>'wait-pay','u_handle'=>[
//                self::U_ORDER_HANDLE_SURE_REC=>['rec_mode'=>1],
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

        //验证是否有完成过订单流程-普通商品有折扣
        $goods_per = 0;
        if($user_model->checkOrderFlowComplete()){
            $n_per = SysSetting::getContent('n_per');
            is_numeric($n_per) && $n_per>0 && $goods_per = $n_per;
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
                $goods_arr['h_per'] = empty($vo['linkGoods']['mode'])?$goods_per:0; // 普通商品折扣优惠
                $h_per_price = empty($goods_arr['h_per'])?0:(1-$goods_arr['h_per'])*$vo['price']; //优惠价
                $h_per_price = empty($h_per_price) ? 0 : $h_per_price<=0.01?0.01:$h_per_price; //优惠价
                $goods_arr['h_per_price'] = $h_per_price; // 普通商品折扣优惠
                $goods_data[]=  $goods_arr;
            }
        }
        //计算金额相关数据
        $money = [
            'money' => 0.00 ,//总金额
            'dis_money' => 0.00 ,//优惠金额
            'goods_money' => 0.00 ,//商品总金额
            'pay_money' => 0.00 ,//实际支付总金额
            'total_money_no_freight' => 0.00 ,//不含运费总额
            'freight_money' => 0.00 ,//运费金额
            'taxation_money' => 0.00 ,//税费总金额
        ];
        foreach ($goods_data as $vo){
            $dis_price = $vo['price']*$vo['buy_num']*$vo['h_per']; // 折扣优惠金额
            $goods_price = $vo['price']*$vo['buy_num']; // 购买金额
            $freight_money = empty($vo['goods_info']['freight_money'])?0:$vo['goods_info']['freight_money']*$vo['buy_num']; // 运费金额
            $taxation_money = 0.00;////$vo['taxation_money']*$vo['buy_num']; // 税费金额

            $money['money'] += $goods_price+$freight_money+$taxation_money;
            $money['dis_money'] += $dis_price;
            $money['total_money_no_freight'] += $goods_price+$taxation_money;
            $money['goods_money'] += $goods_price;
            $money['pay_money'] += $goods_price+$freight_money+$taxation_money-$dis_price;
            $money['pay_money_no_freight'] += $goods_price+$taxation_money-$dis_price; //不含运费跟优惠的价格
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
        //收货方式
        $recive_mode = isset($input_data['recive_mode'])?$input_data['recive_mode']:1;

        if($recive_mode && empty($model_addr)) throw new \Exception('请选择收货地址');
        if(empty($goods_info)) throw new \Exception('请选择购买商品');
        
        //备注
        $remark = empty($input_data['message'])?'':trim($input_data['message']);
        //发票
        $invoice_type = isset($input_data['fapiao'])?$input_data['fapiao']:0;
        //虚拟豆数量
        $inv_pear = isset($input_data['inv_pear'])?$input_data['inv_pear']:0;
        if($inv_pear<0) throw new \Exception('消费豆只能为正数');
        if(!empty($inv_pear) && $inv_pear > $model_user->getAttribute('consum_wallet')){
            throw new \Exception('消费豆不足');
        }
        //虚拟豆抵扣金额
        $inv_pear_per = self::getPropInfo('inv_pear_per');
        $inv_pear_dis_money = is_numeric($inv_pear_per)?$inv_pear_per*$inv_pear:0.00;

        //发票数据
        $invoice_content = isset($input_data['invoice'])?$input_data['invoice']:[];
        $invoice_content = isset($invoice_content[$invoice_type])?$invoice_content[$invoice_type]:[];
        $invoice_data = [];//发票数据
        //发票模版
        $invoice_temp = self::getPropInfo('fields_invoice',$invoice_type,'input');
        if(!empty($invoice_temp) && is_array($invoice_temp) && is_array($invoice_content)){
            foreach ($invoice_content as $key=>$vo){
                $invoice_type_name = isset($invoice_temp[$key])?$invoice_temp[$key]['name']:'';
                if(empty($vo)){
                    throw new \Exception('请输入发票信息:'.$invoice_type_name);
                }
                $invoice_data[] =[
                    'key'    => $key,
                    'name'   => $invoice_type_name,
                    'value'  =>  $vo,
                ];
            }
        }
        //订单数据
        $model_order = $this;
        $model_order->no = self::getOrderNo();
        $model_order->uid = $model_user->id;
        $model_order->admin_id = $model_user->getAttribute('admin_id');//订单门店

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

        //消费都是否使用过多
        if($inv_pear_dis_money>$model_order->money){
            throw new \Exception('该订单最多只能抵扣:'.$model_order->money.',已超出抵扣数量');
        }


        //使用虚拟豆
        $model_order->use_inv_pear = $inv_pear;
        $model_order->inv_pear_dis_money = $inv_pear_dis_money;

        //优惠金额
        $model_order->dis_money = $inv_pear_dis_money+$money['dis_money']; //总优惠金额
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

            //消费豆抵扣消费
            $inv_pear>0 && $model_user->handleConsumWallet(-$inv_pear,$model_order->id,'使用消费豆抵扣:'.$inv_pear_dis_money,[],1,0,8);

            //快递
            if($recive_mode && !empty($model_addr)){
                //保存收货地址
                $model_order_addr = new OrderAddr();
                $model_order_addr->oid=$model_order->id;
                $model_order_addr->phone=!empty($model_addr['phone'])?$model_addr['phone']:'';
                $model_order_addr->username=!empty($model_addr['username'])?$model_addr['username']:'';
                $model_order_addr->addr=!empty($model_addr['addr'])?$model_addr['addr']:'';
                $model_order_addr->addr_extra=!empty($model_addr['addr_extra'])?$model_addr['addr_extra']:'';
                $model_order_addr->zip_code=!empty($model_addr['zip_code'])?$model_addr['zip_code']:null;
                $model_order_addr->save(false);
            }
            

            //商品数据
            foreach($goods_info as $vo){
                $model_order_goods = new OrderGoods();
                $model_order_goods->oid = $model_order->id;
                $model_order_goods->gid = $vo['gid'];
                $model_order_goods->g_mode = $vo['linkGoods']['mode'];//分佣模式
                $model_order_goods->sku_id = $vo['id'];
                $model_order_goods->price = $vo['price'];
                $model_order_goods->h_per = $vo['h_per'];//折扣
                $model_order_goods->pay_price = empty($vo['h_per_price'])?$vo['price']:$vo['h_per_price']; //商品实际支付金额
                $model_order_goods->num = $vo['buy_num'];
                $model_order_goods->pay_money = $model_order_goods->pay_price*$vo['buy_num'];//商品成交总价
                $model_order_goods->freight_money = $vo['linkGoods']['freight_money'];//商品成交总价
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

    /**
     * 订单支付
     * @param User $model_user  当前操作用户
     * @param int $order_id  订单id
     * @param array $input_data  用户请求数据
     * @throws
     * @return array|null
     * */
    public static function pay(User $model_user,$order_id,array $input_data =[])
    {
        $pay_way = empty($input_data['pay_way'])?0:$input_data['pay_way'];

        $model = self::findOne($order_id);
        if(empty($model)) throw new \Exception('订单信息异常');
        //可操作选项
        $sure_handle = $model->getStepFlowInfo($model['step_flow'],'u_handle');
        if(!is_array($sure_handle) || !in_array(self::U_ORDER_HANDLE_PAY,$sure_handle))  throw new \Exception('订单未处于待支付状态');

        if(!array_key_exists($pay_way,self::$fields_pay_way))  throw new \Exception('支付方式异常');
        //订单实际支付金额
        $pay_money = $model->getAttribute('pay_money');
        $model->pay_way = $pay_way;

        if($model->pay_way==1){
            //线下流程

        }elseif($model->pay_way==2){
            //余额支付
            $wallet = $model_user->getAttribute('wallet');
            if($wallet-$pay_money<0)  throw new \Exception('余额不足无法进行支付');
            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            //订单已支付状态调整
            $model->order_success();
            $model_user->handleWallet(-$pay_money,$order_id,'订单余额支付');

        }else{
            //微信支付
            $wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
            $open_id = isset($input_data['open_id'])?$input_data['open_id']:'';
            try{

                $jsApiParameters = $wx_object->handleJsApiPay($open_id,$model);

            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }


        }

        try{
            //消费金豆消费

            $model->save(false);
            isset($transaction) && $transaction->isActive && $transaction->commit();
        }catch (\Exception $e){
            isset($transaction) && $transaction->isActive && $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }

        if(isset($jsApiParameters)) return ['jsapi',$jsApiParameters];

    }

    //调整订单支付已完成
    protected function order_success()
    {
        if(!empty($this->step_flow)){
            return true;
        }
        //调整订单信息
        $this->step_flow = 1;
        $this->status = 1;
        $this->pay_time = time();
        return $this->save(false);
    }

    //订单回调通知
    public static function handleNotify($order_no,array $data)
    {
        $model = self::find()->where(['no'=>$order_no])->limit(1)->one();
        if(empty($model)){
            return;
        }
        //保存第三方支付信息
        $model->setAttribute('third_pay_info',json_encode($data));
        return $model->order_success();
    }

    //订单数据
    public function getOrderPayInfo()
    {
        return [
            'body' => '订单支付',
            'attach' => 'attach',
            'no' => $this->getAttribute('no'),
//            'pay_money' => $this->getAttribute('pay_money'),
            'pay_money' => 0.01,
            'expire_time' => 600,
            'goods_tag' => 'goods',
            'notify_url' => \yii\helpers\Url::to(['wechat/notify'],true),
        ];
//        $input->SetBody("test");
//        $input->SetAttach("test");
//        $input->SetOut_trade_no("sdkphp".date("YmdHis"));
//        $input->SetTotal_fee("1");
//        $input->SetTime_start(date("YmdHis"));
//        $input->SetTime_expire(date("YmdHis", time() + 600));
//        $input->SetGoods_tag("test");
//        $input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
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
            //订单用户
            $model_user = User::findOne($model['uid']);
        }else{
            $model_user = $user_model;
            if(empty($model) || $model['uid']!=$user_model->id)  throw new \Exception('订单数据异常');
            $handle_action = $model->getUserHandleAction();
            if(!in_array(self::U_ORDER_HANDLE_DEL,$handle_action))  throw new \Exception('订单状态未处于可删除状态');
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            !empty($user_model) && $model->_handle_back_inv_pear($model_user);
            $model->delete();
            $is_delete = $model->getAttribute(self::getSoftDeleteField());
            if(!$is_delete) throw new \Exception('删除失败');
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }


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
            //订单用户
            $model_user = User::findOne($model['uid']);
        }else{
            $model_user = $user_model;
            if(empty($model) || $model['uid']!=$user_model->id)  throw new \Exception('订单数据异常');
            $handle_action = $model->getUserHandleAction();
            if(!in_array(self::U_ORDER_HANDLE_CANCEL,$handle_action))  throw new \Exception('订单状态未处于取消状态');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            !empty($user_model) && $model->_handle_back_inv_pear($model_user);
            $model->status = 2;
            $model->cancel_time = time();
            $model->save(false);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 订单取消/删除 消费豆退还处理
     * */
    private function _handle_back_inv_pear(User $user_model)
    {
        //使用消费豆
        $use_inv_pear = $this->getAttribute('use_inv_pear');

        if($use_inv_pear > 0){
            //订单取消动作
            //返还消费豆
            $user_model->handleConsumWallet($use_inv_pear,$this->getAttribute('id'),'订单被取消返还消费豆:'.$use_inv_pear,[],1,0,9);
            $this->pay_money = $this->pay_money+$this->inv_pear_dis_money;
            $this->use_inv_pear = 0;
            $this->inv_pear_dis_money = 0;
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
//            var_dump($group_money);exit;
            //发放佣金--推荐/固定
            if(!empty($com_money)){
                foreach ($com_money as $uid=>$vo){
                    $model_user = User::findOne($uid);
                    $com_money_cal = empty($vo['money'])?0:$vo['money'];
                    if(!empty($model_user)){
                        //获得健康豆
                        $dep_money = intval($com_money_cal*self::COM_DES_PER*100)/100;
                        $model_user->handleDepositMoney($dep_money,$model->getAttribute('id'),'会员号:'.$model_user['number'].'消费获得健康豆:'.$dep_money,$vo,1);
                        //获得消费豆
                        $com_money = intval($com_money_cal*self::COM_CUS_PER*100)/100;
                        $model_user->handleConsumWallet($com_money,$model->getAttribute('id'),'会员号:'.$model_user['number'].'消费获得消费豆:'.$com_money,$vo,1);
                    }

                }
            }

            //发放佣金--团队
            if(!empty($group_money)){
                foreach ($group_money as $uid=>$vo){
                    $model_user = User::findOne($uid);
                    $com_money_cal = empty($vo['money'])?0:$vo['money'];
                    //获得个人获得团队提成
                    $model_user->handleTeamWallet($com_money_cal,$model->getAttribute('id'),'会员号:'.$model_user_buy['number'].'消费获得团队提成增加:'.$com_money_cal,$vo,1);
                    if(!empty($model_user)){
                        //获得健康豆
                        $dep_money = intval($com_money_cal*self::COM_DES_PER*100)/100;
                        $model_user->handleDepositMoney($dep_money,$model->getAttribute('id'),'会员号:'.$model_user_buy['number'].'消费'.'会员号:'.$model_user['number'].'获得团队奖励健康豆:'.$dep_money,$vo,1,1);
                        //获得消费豆
                        $com_money = intval($com_money_cal*self::COM_CUS_PER*100)/100;
                        $model_user->handleConsumWallet($com_money,$model->getAttribute('id'),'会员号:'.$model_user_buy['number'].'消费'.'会员号:'.$model_user['number'].'获得团队奖励消费豆:'.$com_money,$vo,1,1);
                    }
                }
            }

            //我的团队金增加
            $model_user_buy->handleTeamWalletFull($model->getAttribute('need_pay_money'));
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
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->step_flow = 1; //进入发货流程
            $this->status = 1;
            $this->pay_time = time();
            $this->save(false);

            //增加用户累计消费额度
            $user_model = User::findOne($this->getAttribute('uid'));
            if(!empty($user_model)){
                $user_model->handleConsumMoney($this->getAttribute('need_pay_money'));
            }
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            \Yii::info('状态变更异常:'.$e->getMessage(),'sure_pay');
        }

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
//         $model_user_buy_link = ['887','1463','4643','1297','2312','12','14','16','68','369','837'];
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


        //团队提成
        $group_com_total_money = 0.00;
        //佣金信息
        $default_com_data = $fixed_com_data  = $recommend_com_data = [
            'money' => 0,
            'num' => 0,
            'data'  => []
        ];

        foreach ($order_goods as $vo) {
            $num = $vo['num'];
            $total_money = $vo['pay_money'];//商品实际支付价格
            $com_data = [
                'money' => $vo['pay_price'],//商品实际支付价格
                'total_money' => $total_money,
                'num' => $vo['num'],
                'g_mode' => $vo['g_mode'],//商品模式
            ];

            if($vo['g_mode']){
                //增加团队奖
                $group_com_total_money+=$total_money;
                //固定
                $fixed_com_data['num']  += $num;
                $fixed_com_data['money'] += $total_money;
                array_push($fixed_com_data['data'],$com_data);

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
        //提成总额
//        $nor_money = $fix_money = $rec_money = 0.00;
        //普通商品默认提成
        if($default_com_data['money']>0){
            $get_money = $default_com_data['money']*$direct_push_per;
            $com_money[$direct_user_id] = [
                'money' => $get_money, //所得佣金
                'data'  => [array_merge(['sty'=>$direct_push_per,'get_money'=>$get_money,'g_type'=>0],$default_com_data)],
            ];
        }
        //模式奖励
        foreach ($fixed as $key=>$gd){
            $get_money = $fixed_com_data['num']*$gd; //获得模式奖励

            if(isset($model_user_buy_link[$key]) && $fixed_com_data['num']>0){
                $fixed_user_id = $model_user_buy_link[$key];
                if(array_key_exists($fixed_user_id, $com_money)) {

                    $com_money[$fixed_user_id]['money'] += $get_money;
                    array_push($com_money[$fixed_user_id]['data'],array_merge(['sty'=>$gd,'get_money'=>$get_money,'g_type'=>1],$fixed_com_data));


                }else{
                    $com_money[$fixed_user_id]=[
                        'money'   => $get_money,
                        'data'    => [array_merge(['sty'=>$gd,'get_money'=>$get_money,'g_type'=>1],$fixed_com_data)],
                    ];
                }

            }else{
                //没人直接
                break;
            }
        }

        //推荐模式
        foreach ($recommend as $key=>$gd){
            $get_money = $recommend_com_data['money']*$gd;//奖励金额
            if(isset($model_user_buy_link[$key]) && $recommend_com_data['money']>0){
                $fixed_user_id = $model_user_buy_link[$key];
                if(array_key_exists($fixed_user_id, $com_money)) {
                    $com_money[$fixed_user_id]['money'] += $get_money;
                    array_push($com_money[$fixed_user_id]['data'],array_merge(['sty'=>$gd,'get_money'=>$get_money,'g_type'=>2],$recommend_com_data));
                }else{
                    $com_money[$fixed_user_id]=[
                        'money'   => $get_money,
                        'data'    => [array_merge(['sty'=>$gd,'get_money'=>$get_money,'g_type'=>2],$recommend_com_data)],
                    ];
                }

            }else{
                //没人直接
                break;
            }
        }
        //团队提成
        if($group_com_total_money>0){
            //团队模式
            $rev_users = array_reverse($model_user_buy_link);
            //获取团队贡献金额
            $group_users = User::find()->asArray()->select('id,team_wallet_full')->where(['id'=>$model_user_buy_link])->all();
            $group_users = array_column($group_users,null,'id');
            //离我最近的几个用户拿提成
            $link_user_top_money = [];
            foreach ($rev_users  as $vo){
                if(isset($group_users[$vo])){
                    $link_user_top_money[$group_users[$vo]['team_wallet_full']] =  $group_users[$vo];
                }
            }
            krsort($link_user_top_money,SORT_NUMERIC);

            //处理金额key
            $link_user_top_money= array_values($link_user_top_money);
            $group_record_current_index = false;
            $group_record_all_per = []; //记录所有比例
            $group_new_users = [];
            foreach ($link_user_top_money as $vo){
                list($group_award_index,$group_award_per,$is_over) = $this->_get_group_per($vo['team_wallet_full']);
                if($group_award_index===false || $group_award_per<=0){
                    //直接结束 //没有达到标准 比例小于0、已处理过同比例的数据
                    break;
                }
                //记录此次比例
                // $group_record_current_index = $group_award_index;
                // array_push($group_record_all_per,$group_award_per);
                $vo['com_per_key'] = $group_award_index; //奖励索引
                $vo['com_per'] = $group_award_per; //奖励索引
                array_push($group_new_users, $vo);
                //结束
//                if($is_over) break;
            }
    //        var_dump($group_new_users);exit;
            //重新计算提成人员--奖励信息
            $group_new_users_com = array_values(array_column($group_new_users, null,'com_per_key'));

            foreach ($group_new_users_com as $vo) {
                array_push($group_record_all_per,$vo['com_per']);
            }
            //最终团队比例
            $group_per = [];

            if(isset($is_over) && $is_over===true){
                $group_per[] = array_shift($group_record_all_per);
            }

            //计算比例
            !empty($group_record_all_per) && $group_per = array_merge($group_per,$this->_handle_team_per($group_record_all_per));
            //处理提成
            foreach ($group_new_users_com as $key=>$lgm){

                if(isset($group_per[$key])){
                    $per = $group_per[$key];
                    $group_money[$lgm['id']]=[
                        'money'   => $group_com_total_money*$per,
                        'data'    => array_merge(['sty'=>$per,'com_money'=>$group_com_total_money],$lgm),
                    ];
                }else{
                    break;
                }

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
     * @param float $team_wallet 团队金额
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
        $is_over = false;
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
                        if($team_wallet >= $cond[0]*$cond_multiple){
                            $is_over = true; //达到了某一个范围可以结束
                            $team_group_index = $cond_key;
                            $team_group_per = $group_award[$cond_key];
                            break;
                        }
                    }

                }
            }
        }

        return [$team_group_index,$team_group_per,$is_over];
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
                    $current_per = $max_per-$team_per[$key+1];
                    array_push($per,$current_per);
                    $max_per-=$current_per;//递减
                }else{
                    //最后一个元素
                    array_push($per,$max_per);
                }
            }
        }
        return $per;
    }


    /**
     * 自动添加时间戳，序列化参数
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //订单处理日志
        $behaviors[]=\common\components\OrderBehavior::className();

        //开启软删除
        $behaviors['softDeleteBehavior'] = [
            'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::className(),
            'softDeleteAttributeValues' => [
                self::getSoftDeleteField() => time(),
            ],
            'replaceRegularDelete' => true // mutate native `delete()` method
        ];
        return $behaviors;
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
        return $this->hasMany(UserLog::className(),['cond'=>'id'])->where(['origin_type'=>1,'type'=>[1,2,3]]);
    }

    //订单门店
    public function getLinkStore()
    {
        return $this->hasOne(SysManager::className(),['id'=>'admin_id']);
    }

}
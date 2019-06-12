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

    private static function oldDbLink()
    {
        //旧表数据
        return  new \yii\db\Connection([
            'dsn' => 'mysql:host=sql.l35.vhostgo.com;dbname=szhuaqingsu',
            'username' => 'szhuaqingsu',
            'password' => 'SZhulian2030',
            'charset' => 'utf8',
        ]);

    }

/*
 * CREATE TABLE `member` (
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6923 DEFAULT CHARSET=utf8;
 * 同步用户数据
 * */
    public function actionSycData()
    {
//        echo '同步数据开始<br/>';
//        echo '-----同步用户数据--------<br/>';
        $old_user_query = self::oldDbLink()->createCommand('select * from member order by id asc limit :start,:end ');
        $len=10;
        $i=0;
        $insert_num = [];
        for(;;){
            $old_user_data = $old_user_query->bindValues([':start'=>$i*$len,':end'=>$len])->queryAll();
            $write_user_data = [];
            foreach ($old_user_data as $vo){
                //新表需要录入的数据
                array_push($write_user_data,[
                    'id'            =>$vo['id'],
                    'number'        =>$vo['number'],
                    'username'      =>$vo['username'],
                    'sex'           =>$vo['sex'],
                    'open_id'       =>$vo['open_id'],
                    'image'         =>$vo['image'],
                    'wechat_qrcode_img' =>null,     //微信分享二维码图片地址
                    'type'          =>$vo['type'],  //无用[del]
                    'consume_type'  =>null,         //消费等级
                    'admin_id'      => $vo['admin_id']?$vo['admin_id']:1,    //门店会员
                    'direct_id'     =>$vo['direct_id'], //直推用户id--目前没有用的字段[del]
                    'phone'         =>$vo['phone'],     //手机号码
                    'tuijian_id'    =>$vo['tuijian_id'],//推荐人id
                    'tuijian_type'  =>$vo['tuijian_type'],//0没设置过 1设置过推荐人[del]
                    'fl_uid_all'    =>null,  // 队伍链【新增】
                    'team_wallet_full'  =>0,  // 团队提成【新增】
                    'team_wallet'   =>$vo['team_wallet'],  //团队提成团队个人提成
                    'consum_wallet' =>$vo['consum_wallet'], //消费豆
                    'deposit_money' =>$vo['deposit_money'], //健康豆
                    'wallet'        =>$vo['wallet'],  //钱包
                    'consum_money'  =>null, //累计消费额度
                    'integral'      =>$vo['integral'],    //积分【del】
                    'tprice'        =>$vo['tprice'],    //金豆【del】
                    'tstate'        =>$vo['tstate'],    //[del]
                    'txtime'        =>$vo['txtime'],    //[del]
                    'last_sing_time'=>$vo['last_sing_time'],//'上一次签到时间'【del】
                    'total_day'     =>$vo['total_day'],  //连续签到天数【del】
                    'qiandao_pro_day'=>$vo['qiandao_pro_day'], //1套可以打5天，这里存的是有效需打卡的天数【del】
                    'youxiao_num'   =>$vo['youxiao_num'],      //0 到5时打卡奖励并且设置为0【del】
                    'youxiao_type'  =>$vo['youxiao_type'],    //0未奖励 1开始奖励了，中途断了都不算奖励了【del】
                    'sharetimes'    =>$vo['sharetimes'],//【del】
                    'password'      =>null,  //重置所有密码
                    'salt'          =>null,  //重置所有密码盐
                    'ztime_state'   =>$vo['ztime_state'],  //提现提醒 【del】
                    'bank_id'       =>$vo['bank_id'],   //  【del】
                    'sbeans'        =>$vo['sbeans'],     // 【del】
                    'union_id'      =>$vo['union_id'],
                    'deposit'       =>$vo['deposit'],  //押金剩余 直销模式 【del】
                    'places'        =>$vo['places'],   //名额【del】
                    'is_ninety'     =>$vo['is_ninety'],     //是不是奖过直推人90奖励 0-没有  1-有【del】
                    'idcard'        =>$vo['idcard'],   //身份证【del】
                    'usersname'     =>$vo['usersname'], //真实姓名【del】
                    'birthday'      =>$vo['birthday'],  //生日【del】
                    'address'       =>$vo['address'],   //地址【addr】
                    'weixin'        =>$vo['weixin'],    //微信号【del】
                    'back_deposit'  =>$vo['back_deposit'],//退回押金 可体现 不扣手续费跟重复消费【del】
                    'txtype'        =>$vo['txtype'],    //提现类型 0 金豆 1押金【del】
                    'dl_addtime'      =>$vo['dl_addtime'],
                    'create_time'     =>$vo['addtime']?$vo['addtime']:time(),
                    'update_time'=>$vo['addtime']?$vo['addtime']:time(),
                    'delete_time'=>null,

                ]);
            }
            if(empty($write_user_data)){
                //直接结束
                break;
            }

            $i_fields = array_keys($write_user_data[0]);
            $line = \Yii::$app->db->createCommand()->batchInsert('zll_user',$i_fields,$write_user_data)->execute();
            array_push($insert_num,$line);

//            if($i>3) break;

            $i+=1;

        }
        var_dump($insert_num);

//        return '--------同步结束-----------<br/>';
    }

    /**
     * 同步其它数据
     * */
    public function actionOtherData()
    {
        //当前执行时间
        $current_time = time();
        //记录处理数据
        $record_data = [];
        //同步地址
        $address = self::oldDbLink()->createCommand('select * from addres')->queryAll();
        $write_user_address_data = [];
        foreach ($address as $vo){
            array_push($write_user_address_data,[
                'id'=>$vo['id'],
                'uid'=>$vo['member_id'],
                'phone'=>$vo['phone'],
                'username'=>$vo['name'],
                'addr'=>$vo['province'].' '.$vo['city'].' '.$vo['district'],
                'addr_extra'=>$vo['detail'],
                'is_default'=>0,
                'zip_code'=>$vo['youzheng'],
                'create_time'=>$current_time,
                'update_time'=>$current_time,
                'delete_time'=>$vo['del']?$current_time:null,
            ]);
        }

        //写入数据
        if(!empty($write_user_address_data)){
            $i_fields = array_keys($write_user_address_data[0]);
            $line = \Yii::$app->db->createCommand()->batchInsert('zll_user_addr',$i_fields,$write_user_address_data)->execute();
            array_push($record_data,['user_addr'=>$line]);
        }

        //用户已有银行卡
        $bankcard = self::oldDbLink()->createCommand('select * from bankcard')->queryAll();
        $write_user_bankcard_data = [];
        foreach ($bankcard as $vo){
            array_push($write_user_bankcard_data,[
                'id'=>$vo['id'],
                'uid'=>$vo['member_id'],
                'name'=>$vo['bank_name'],
                'number'=>$vo['bank_number'],
                'username'=>$vo['bank_username'],
                'phone'=>$vo['phone'],
                'up'=>$vo['image1'],
                'down'=>$vo['image2'],
                'status'=>1,
                'create_time'=>$current_time,
                'update_time'=>$current_time,
                'delete_time'=>$vo['del']?$current_time:null,
            ]);
        }

        //写入数据
        if(!empty($write_user_bankcard_data)){
            $i_fields = array_keys($write_user_bankcard_data[0]);
            $line = \Yii::$app->db->createCommand()->batchInsert('zll_user_bank_card',$i_fields,$write_user_bankcard_data)->execute();
            array_push($record_data,['zll_user_bank_card'=>$line]);
        }


        var_dump($record_data);
    }
    /**
     *
    `addres_id` int(11) DEFAULT NULL,
    `shoptype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0提货 1入库',
    `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未读 1已读',
    `cart_id` varchar(255) DEFAULT NULL,
    `prices` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '总金额 分单位开始',
    `note` varchar(255) DEFAULT NULL COMMENT '备注',
    `ztime_state` int(1) NOT NULL DEFAULT '0',
    `product_id` varchar(20) DEFAULT NULL COMMENT '产品编号 ，隔开',
    `fahuo_time` int(20) NOT NULL DEFAULT '0' COMMENT '发货时间',
     *
     * 同步订单数据
     * */
    public function actionSycOrderData()
    {
        $pay_way = ['WeChat'=>0,'offline'=>0,'wallet'=>0];
        //当前执行时间
        $current_time = time();

        $sql  = self::oldDbLink()->createCommand('select * from orderlist order by id asc limit :start,:end ');
        $len=10;
        $i=0;
        $insert_num = [];
        $record_addr=[];
        for(;;){
            $old_data = $sql->bindValues([':start'=>$i*$len,':end'=>$len])->queryAll();
            $write_data = $write_logistics_data = $write_addr_data = $write_goods_data = [];
            foreach ($old_data as $vo){
                if($vo['fapiao']){
                    $invoice_content = [
                        ['key'=> 0,'name'=> '单位名称','value' =>  $vo['companyname']],
                        ['key'=> 1,'name'=> '纳税人识别号','value' =>  $vo['taxpayernumber']],
                        ['key'=> 2,'name'=> '注册地址','value' =>  $vo['registeaddress']],
                        ['key'=> 3,'name'=> '注册电话','value' =>  $vo['registetelephone']],
                        ['key'=> 4,'name'=> '开户银行','value' =>  $vo['bank']],
                        ['key'=> 5,'name'=> '银行帐号','value' =>  $vo['bankaccount']],
                    ];
                }

                //0待付款 1待发货 2待确定收货 3已完成
                $step_flow = 0;
                $status = 0;
                $is_send = 0;
                $send_start_time = null;
                $send_end_time = null;

                $is_receive = 0;
                $receive_start_time = null;
                $receive_end_time = null;
                $complete_time = null;
                if($vo['state']==1){
                    $step_flow = 1;
                    $status = 1;
                    $is_send = 0;
                    $send_start_time = $current_time;
                }elseif ($vo['status']==2){
                    $step_flow = 2;
                    $status = 1;
                    $is_send = 1;
                    $send_start_time = $current_time;
                    $send_end_time = $vo['fahuo_time'];

                    $receive_start_time = $current_time;
                }elseif ($vo['status']==3){
                    $step_flow = 3;
                    $status = 3;
                    $is_send = 1;
                    $send_start_time = $current_time;
                    $send_end_time = $vo['fahuo_time'];

                    $is_receive = 1;
                    $receive_start_time = $current_time;
                    $receive_end_time = $current_time;
                    $complete_time = $current_time;
                }

                //查询地址信息
                if(!array_key_exists($vo['addres_id'],$record_addr)){
                    $addre_info = self::oldDbLink()->createCommand('select * from addres where id=:id ')->bindValues([':id'=>$vo['addres_id']])->queryOne();
                    $record_addr[$vo['addres_id']] = $addre_info;
                }

                //查询关联商品
                $goods_info = self::oldDbLink()->createCommand('select * from cart where id in (:cart_id)')->bindValues([':cart_id'=>$vo['cart_id']])->queryAll();
                foreach ($goods_info as $gi){
                    array_push($write_goods_data,[
                        'oid'=>$vo['id'],
                        'gid'=>-1,
                        'price'=>$gi['price'],
                        'h_per'=>0,
                        'pay_price'=>$gi['price'],
                        'num'=>$gi['num'],
                        'pay_money'=>$gi['price']*$gi['num'],
                        'name'=>$gi['name'],
                    ]);
                }

                //新表需要录入的数据
                array_push($write_data,[
                    'id'                =>$vo['id'],
                    'admin_id'          =>$vo['admin_id'], //门店id
                    'no'                =>$vo['sn'],       //编号
                    'uid'               =>$vo['member_id'],
                    'pay_way'           =>isset($pay_way[$vo['payment']])?$pay_way[$vo['payment']]:null,
                    'money'             =>$vo['prices'],
                    'need_pay_money'    =>$vo['prices'],
                    'pay_money'         =>$vo['prices'],
                    'use_inv_pear'      =>0,  //消费豆兑换
                    'inv_pear_dis_money'=>0,  //消费豆抵扣额度
                    'dis_money'         =>0,
                    'freight_money'     =>0,
                    'taxation_money'    =>0,
                    'rec_mode'          =>$vo['tihuo_type']?0:1,
                    'invoice_type'      =>$vo['fapiao'],  //发票类型
                    'is_back_pear'      =>0,//取消是否返回消费豆

                    'step_flow'         =>$step_flow,
                    'status'            =>$status,

                    'pay_time'          =>empty($vo['pay_addtime'])?null:$vo['pay_addtime'],
                    'm_id_opt_del'      =>null,
                    'm_id_opt_pay'      =>null,
                    'm_id_opt_cancel'   =>null,
                    'cancel_time'       =>null,
                    'complete_time'     =>$complete_time,

                    'is_send'           =>$is_send,
                    'send_start_time'   =>$send_start_time,
                    'send_end_time'     =>$send_end_time,
                    'is_receive'        =>$is_receive,
                    'receive_start_time'=>$receive_start_time,
                    'receive_end_time'  =>$receive_end_time,

                    'invoice_content'    => isset($invoice_content)?json_encode($invoice_content):null,
                    'remark'             =>$vo['message'],
                    'third_pay_info'     =>null,//第三方支付信息
                    'create_time'        =>$vo['addtime']?$vo['addtime']:$current_time,
                    'update_time'        =>$current_time,
                    'delete_time'        =>$vo['del']?$current_time:null,
                ]);

                //物流数据
                array_push($write_logistics_data,[
                    'oid'=>$vo['id'],
                    'no'=>$vo['wuliu'],
                    'company'=>$vo['wuliuname'],
                    'money'=>$vo['prices_kuaidi'],
                ]);
                //收货地址
                array_push($write_addr_data,[
                    'oid'=>$vo['id'],
                    'phone'=>$addre_info['phone'],
                    'username'=>$addre_info['name'],
                    'addr'=>$addre_info['province'].' '.$addre_info['city'].' '.$addre_info['district'],
                    'addr_extra'=>$addre_info['detail'],
                    'zip_code'=>$addre_info['youzheng'],
                ]);
            }
            if(empty($write_data)){
                //直接结束
                break;
            }

            $i_fields = array_keys($write_data[0]);
            $line = \Yii::$app->db->createCommand()->batchInsert('zll_order',$i_fields,$write_data)->execute();
            array_push($insert_num,['order'=>$line]);

            if(!empty($write_logistics_data)){
                $i_fields = array_keys($write_logistics_data[0]);
                $line = \Yii::$app->db->createCommand()->batchInsert('zll_order_logistics',$i_fields,$write_logistics_data)->execute();
                array_push($insert_num,['order_logistics'=>$line]);
            }

            if(!empty($write_addr_data)){
                $i_fields = array_keys($write_addr_data[0]);
                $line = \Yii::$app->db->createCommand()->batchInsert('zll_order_addr',$i_fields,$write_addr_data)->execute();
                array_push($insert_num,['order_addr'=>$line]);
            }

            if(!empty($write_goods_data)){
                $i_fields = array_keys($write_goods_data[0]);
                $line = \Yii::$app->db->createCommand()->batchInsert('zll_order_goods',$i_fields,$write_goods_data)->execute();
                array_push($insert_num,['order_goods'=>$line]);
            }


//            if($i>3) break;

            $i+=1;

        }
        var_dump($insert_num);
    }
}

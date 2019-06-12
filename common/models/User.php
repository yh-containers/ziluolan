<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class User extends BaseModel
{
    use SoftDelete;
    //微信登录
    const USER_SESSION_LOGIN_INFO = 'USER_SESSION_LOGIN_INFO';
    const DEPOSIT_2_WALLET_PER = 1;//健康豆兑换金豆比例
    const WITHDRAW_MONEY_PER = 1;//用户提现比例
    const WITHDRAW_MONEY_COM_PER = 0.05;//用户提现比例
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static $fields_sex = ['未知','男','女','保密'];

    public static $fields_consume_type = [
        [],
        ['name'=>'C级','con'=>[1]],
        ['name'=>'P级','con'=>[300000]],
        ['name'=>'S级','con'=>[3000000]],
    ];

    /**
     * 检测用户微信登录信息
     * @return null|self
     * */
    public static function checkWxLoginInfo()
    {
        //获取微信认证信息
        $wx_auth_info = \Yii::$app->session->get(\common\components\Wechat::WX_AUTH_USER_INFO);
        if(empty($wx_auth_info)){
            return null;
        }
//        var_dump($wx_auth_info);
        $model = self::find()->where(['open_id'=>$wx_auth_info['openid']])->one();
        if(empty($model)){
            //注册微信用户
            $model = new self();
            !empty($wx_auth_info['nickname']) && $model->username = $wx_auth_info['nickname'];
            !empty($wx_auth_info['sex']) &&  $model->sex = $wx_auth_info['sex'];
            !empty($wx_auth_info['headimgurl']) && $model->image = $wx_auth_info['headimgurl'];
            if(!empty($wx_auth_info['openid'])){
                $model->open_id = $wx_auth_info['openid'];
                //验证用户是否有邀请者
                $sub_model = \common\models\WechatSubscribe::find()->with(['linkReqUser'])->where(['openid'=>$model->open_id])->orderBy('id desc')->one();
                if($sub_model && $sub_model['req_user_id']){
                    //邀请者
                    $model->tuijian_id = $sub_model['req_user_id'];
                    //邀请者-队伍链
                    $fl_uid_all = $model->tuijian_id;
                    if(!empty($sub_model['linkReqUser']) && !empty($sub_model['linkReqUser']['fl_uid_all'])){
                        $fl_uid_all.=','.$sub_model['linkReqUser']['fl_uid_all'];
                    }
                    //保存队伍链
                    $model->fl_uid_all = $fl_uid_all;
                    $model->admin_id = $sub_model['linkReqUser']['admin_id']; //对应门店
                }
            }
//            !empty($wx_auth_info['access_token']) && $model->setAttribute('wx_access_token',$wx_auth_info['access_token']);
//            !empty($wx_auth_info['refresh_token']) && $model->setAttribute('refresh_token',$wx_auth_info['refresh_token']);


        }
//        var_dump($model->getAttributes());exit;
        //保存数据
        $model->save(false);
        $user_id = $model->getAttribute('id');
        if(!$model->getAttribute('number') && $user_id){
            //会员编号
            $number = 100000 + $user_id;
            $model->number='A'.$number;
            $model->save();
        }
        return $model;
    }


    /**
     * 用户登录
     * @return bool
     * */
    public function handleLogin()
    {
        $user_id = $this->getAttribute('id');
        if(empty($user_id)){
           return false;
        }
        //保存session 会话
        $session = \Yii::$app->session;
        $session->isActive || $session->open();

        $session->set(self::USER_SESSION_LOGIN_INFO,[
            'user_id' => $this->getAttribute('id'),
        ]);
        return true;

    }

    //获取用户微信推广二维码
    public function getWechatQrcode()
    {
        $wechat_qrcode_img = $this->getAttribute('wechat_qrcode_img');
        if(empty($wechat_qrcode_img)){
            try{
                $wechat = \Yii::createObject(\Yii::$app->components['wechat']);
                //永久二维码
                list($ticket,$url) = $wechat->qrcode($this->getAttribute('id'),'QR_LIMIT_SCENE');
                if(!empty($ticket)){
                    $wechat_qrcode_img = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
                    //保存二维码
                    $this->wechat_qrcode_img=$wechat_qrcode_img;
                    $this->save(false);
                }
            }catch (\Exception $e){

            }
        }
        return $wechat_qrcode_img;
    }
    /**
     * 添加购物车
     * @param $gid int 商品id
     * @param $sku_id int 商品skuid
     * @param $num int 数量
     * @param $mod bool 调整数量
     * @param $num_step int 增/减 步进值
     * @return bool
     */
    public function addShoppingCart($gid,$sku_id,$num=1,$mod=false,$num_step=0)
    {
        $bool = true;
        $model = UserCart::find()->where(['uid'=>$this->id,'gid'=>$gid,'sid'=>$sku_id])->one();
        if(!empty($model)){
            if($num<0 && $model->num<=1){

            }elseif($mod){
                $model->num= $num+$num_step;
                $bool = $model->save();
            }else{
                $bool = $model->updateCounters(['num'=>$num]);
            }
        }else{
            $model = new UserCart();
            $model->uid = $this->id;
            $model->gid = $gid;
            $model->sid = $sku_id;
            $model->num = $num;
            $bool = $model->save(false);
        }
        return $bool;
    }
    /**
     * 商品收藏
     * @param $goods_id int|array 商品id
     * @param $is_del bool 是否强制删除
     * @throws
     * @return array
     */
    public function goodsCol($goods_id,$is_del=false)
    {
        if(is_array($goods_id)){
            foreach ($goods_id as $gid){
                $this->_handleGoodsCol($gid,true,$is_del);
            }
            return [true,1];
        }else{
            return $this->_handleGoodsCol($goods_id,false,$is_del);
        }

    }
    /**
     * 收藏动作商品
     * @param $gid int 商品id
     * @param $is_force bool 强制收藏数据
     * @param $is_del bool 是否强制删除
     * @return array
     * */
    private function _handleGoodsCol($gid,$is_force=false,$is_del=false)
    {
        $bool = true;
        // 1添加收藏  0取消收藏
        $is_col = 1;
        $model = UserCol::find(true)->where(['uid'=>$this->id,'gid'=>$gid])->one();

        if(!empty($model)){
            $soft_field = UserCol::getSoftDeleteField();
            if(!$is_del && ($is_force || !empty($model[$soft_field]))){
                //有收藏记录 被删除 现在重新收藏
                $model->$soft_field = null;
                $model->col_time = date('Y-m-d H:i:s');
                $state = $model->save();
                $bool = $state!==false?true:false;
            }else{
                $is_col = 0;
                //取消收藏
                $model->$soft_field = time();
                $state = $model->save();
                $bool = $state!==false?true:false;
            }
        }else{
            if(!$is_del){
                //新增收藏
                $model = new UserCol();
                $model->uid = $this->id;
                $model->gid = $gid;
                $model->col_time = date('Y-m-d H:i:s');
                $bool = $model->save(false);
            }

        }
        return [$bool,$is_col];
    }

    /**
     * 购物车商品选中和取消选中效果
     * @var $cart_id int 购物车id
     * @var $is_full_checked int|null 全选和反全选
     * @return array
     */
    public function cartChoose($cart_id,$is_full_checked=null)
    {
        // 1选中 0未选中
        $is_checked = 1;
        $bool = true;
        if(!is_null($is_full_checked)){
            if(!$is_full_checked){
                $is_checked=0;
            }
            //全选
            UserCart::updateAll(['is_checked'=>$is_full_checked?1:0],['uid'=>$this->id]);
        }else{
            $model = UserCart::find()->where(['uid'=>$this->id,'id'=>$cart_id])->one();
            if(empty($model)){

            }else{
                if($model->is_checked==1){
                    //取消选中
                    $model->is_checked = 0;
                    $is_checked = 0;
                }else{
                    $model->is_checked = 1;
                }
                $bool = $model->save(false);
            }
        }

        return [$bool,$is_checked];
    }

    /**
     * 删除购物车
     * */
    public function cartDel(array $cart_id)
    {
        $ids = [];
        foreach ($cart_id as $vo){
            if((is_numeric($vo) && $vo>0 )){
                $ids[] = $vo;
            }
        }
        if(!empty($ids)){
            UserCart::deleteAll(['uid'=>$this->id,'id'=>$ids]);
        }
    }
    /**
     * 消费累计增加
     * @param double $number
     * */
    public function handleConsumMoney($number)
    {
        $this->updateCounters(['consum_money'=>$number]);
        //检测用户等级
        $this->modConsumeType();
    }
    /**
     * 团队金额增加
     * @param double $number
     * */
    public function handleTeamWalletFull($number)
    {
        //增加团队金
        $group_user = $this->getAttribute('fl_uid_all');
        $group_user = empty($group_user)?[]:explode(',',$group_user);
        if($number>0 && !empty($group_user)){
            self::updateAllCounters(['team_wallet_full'=>$number],['id'=>$group_user]);
        }
    }
    //验证是否有完成的下单流程
    public function checkOrderFlowComplete()
    {
        $order_model = Order::find(false)->where(['uid'=>$this->id,'step_flow'=>3,'status'=>3])->limit(1)->one();
        return empty($order_model)?false:true;
    }

    /**
     * 健康豆换金豆
     * @param float $number兑换数量
     * @throws
     * */
    public function dm2w($number)
    {
        $deposit_money = $this->getAttribute('deposit_money');
        if ( $number<=0 || !is_numeric($number) ) throw new \Exception('兑换数量只能为正实数');
        if ( $deposit_money < $number ) throw new \Exception('健康豆不足');
        //检测用户是否有订单

        if(!$this->checkOrderFlowComplete())  throw new \Exception('有订单完成交易才能进行兑换');
        
        //兑换数量
        $change_number = self::DEPOSIT_2_WALLET_PER*$number;

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $before_data = [
                'deposit_money' => $deposit_money,
                'wallet' => $this->getAttribute('wallet'),
                'per' => self::DEPOSIT_2_WALLET_PER,
                'number' => $number,
                'change_number' => $change_number,
            ];
            //兑换-交易
            $this->updateCounters([
                'deposit_money' => -$number,
                'wallet'=> $change_number,
            ]);

            $after_data = [
                'deposit_money' => $this->getAttribute('deposit_money'),
                'wallet' => $this->getAttribute('wallet'),
            ];
            //记录日志
            UserLog::recordLog($this,5,false,false,'使用健康豆'.$number.'换金豆'.$change_number,['before_data'=>$before_data,'after_data'=>$after_data]);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }


    }

    /**
     * 赠送金豆
     * @param string $user_number 用户编号
     * @param float $number 数量
     * @throws
     * */
    public function giveUser($user_number,$number)
    {
        if(empty($user_number)) throw new \Exception('用户编号必须输入');

        $wallet = $this->getAttribute('wallet'); //金豆（余额）
        if ( $number<=0 || !is_numeric($number) ) throw new \Exception('赠送数量只能为正实数');
        if ( $wallet < $number ) throw new \Exception('金豆不足');
        //
        $model_give_user = self::find()->where(['number'=>$user_number])->one();
        if(empty($model_give_user))  throw new \Exception('赠送对象不存在');

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $extra = ['form_user_id'=>$this->getAttribute('id'),'to_user_id'=>$model_give_user->getAttribute('id'),'number'=>$number];
            $this->handleWallet(-$number,false,'赠送'.$number.'金豆给编号为:'.$user_number.'的用户',$extra,0,0,6);
            $model_give_user->handleWallet($number,false,'获得用户编号为:'.$this->getAttribute('number').'赠送的金豆数量:'.$number,$extra,0,0,6);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }


    }

    /**
     * 用户提现
     * */
    public function withdrawConfirm($bank_id,$number)
    {
        if(empty($bank_id)) throw new \Exception('请选择银行卡');
        $wallet = $this->getAttribute('wallet'); //金豆（余额）
        if ( $number<=0 || !is_numeric($number) ) throw new \Exception('提现数量只能为正实数');
        if($number<100)  throw new \Exception('提现金额必须大于100');
        if ( $wallet < $number ) throw new \Exception('金豆不足');

        //查看银行卡
        $transaction = \Yii::$app->db->beginTransaction();
        $model_bank = UserBankCard::findOne($bank_id);
        if(empty($model_bank)) throw new \Exception('银行卡信息异常');
        if($model_bank['uid']!=$this->getAttribute('id')) throw new \Exception('银行卡信息异常2');

        try{
            //创建提现记录
            $model = new UserWithdraw();
            $model->uid = $this->getAttribute('id');
            $model->bid = $bank_id;
            $model->com_money = $number*self::WITHDRAW_MONEY_COM_PER;//手续费
            $model->in_money = $number;
            $model->per = self::WITHDRAW_MONEY_PER;
            $model->out_money = ($number-$model->com_money)*self::WITHDRAW_MONEY_PER;//最终提现金额
            //银行卡信息
            $model->bank_name = $model_bank['name'];
            $model->bank_number = $model_bank['number'];
            $model->bank_username = $model_bank['username'];
            $model->bank_phone = $model_bank['phone'];

            $model->status = 0;
            $model->create_time = date('Y-m-d H:i:s');
            $model->save(false);
            //扣钱
            $this->handleWallet(-$number,$bank_id,'提现'.$number.'元宝到银行卡:'.$model_bank['number'],[],0,0,7);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }

    }



    /**
     * 团队个人金额增加
     * @param double $number
     * */
    public function handleTeamWallet($number,$cond=false,$intro='',array $extra=[],$origin_type = 1)
    {
        //增加团队金
        if($number>0){
            $quota = [$this->team_wallet,$number];
            $this->updateCounters(['team_wallet'=>$number]);
            array_push($quota,$this->getAttribute('team_wallet'));
            //记录日志
            UserLog::recordLog($this,3,$quota,$cond,$intro,$extra,$origin_type,1);
        }

    }

    /**
     * 记录用户健康豆
     * */
    public  function handleDepositMoney($number,$cond=false,$intro='',array $extra=[],$origin_type = 1,$is_group=0)
    {
        $quota = [$this->deposit_money,$number];
        $this->updateCounters(['deposit_money'=>$number]);
        array_push($quota,$this->getAttribute('deposit_money'));
        //记录日志
        UserLog::recordLog($this,1,$quota,$cond,$intro,$extra,$origin_type,$is_group);
    }

    /**
     * 记录用户消费金豆
     * */
    public  function handleConsumWallet($number,$cond=false,$intro='',array $extra=[],$origin_type = 1,$is_group=0,$type=2)
    {
        $quota = [$this->consum_wallet,$number];
        $this->updateCounters(['consum_wallet'=>$number]);
        array_push($quota,$this->consum_wallet);
        //记录日志
        UserLog::recordLog($this,$type,$quota,$cond,$intro,$extra,$origin_type,$is_group);
    }

    /**
     * 用户金豆(钱包)余额
     * */
    public function handleWallet($number,$cond=false,$intro='',array $extra=[],$origin_type = 1,$is_group=0,$type=4)
    {
        $quota = [$this->wallet,$number];
        $this->updateCounters(['wallet'=>$number]);
        array_push($quota,$this->wallet);
        //记录日志
        UserLog::recordLog($this,$type,$quota,$cond,$intro,$extra,$origin_type,$is_group);
    }

    /**
     * 自动添加时间戳，序列化参数
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
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

    //调整用户等级
    public function modConsumeType()
    {
        //当前用户等级
        $current_consume_type = $this->getAttribute('consume_type');
        //累计消费金额
        $consum_money = $this->getAttribute('consum_money');
        $fields_consume_type = self::getPropInfo('fields_consume_type');
        for($i=count($fields_consume_type)-1;$i>0;$i--){
            //条件金额
            $cond_money = empty($fields_consume_type[$i]['con'])?[]:$fields_consume_type[$i]['con'];
            if(count($cond_money)==2){
                if($i>$current_consume_type && $cond_money[0]<=$consum_money && $cond_money[1]>$consum_money){
                    //直接调整用户等级
                    $this->consume_type = $i;
                    $this->save(false);
                    break;
                }
            }elseif (count($cond_money)==1){
                if($i>$current_consume_type && $cond_money[0]<$consum_money){
                    //直接调整用户等级
                    $this->consume_type = $i;
                    $this->save(false);
                    break;
                }
            }else{
                break;
            }
        }



    }



    public function rules()
    {
        $rule = parent::rules(); // TODO: Change the autogenerated stub
        $rule = array_merge($rule,[
            ['type','default','value'=>1],
            ['admin_id','default','value'=>1],
            ['sex','default','value'=>0],
            ['consume_type','default','value'=>0],
            ['tuijian_id','default','value'=>0], //推荐用户id
            ['image','default','value'=>'/assets/images/default.jpg'],
            [['wallet','deposit_money','consum_wallet'],'default','value'=>0.00],
            ['username','safe'],
        ]);
        return $rule;
    }

//    /**
//     * 记录用户金豆
//     * */
//    public  function _setDepositMoney($number,$intro)
//    {
//        $this->updateCounters(['deposit_money'=>$number]);
//    }
//
//    /**
//     * 健康豆转金豆
//     * */
//    public  function _setDepositMoney($number,$intro)
//    {
//        $this->updateCounters(['deposit_money'=>$number]);
//    }

    //
//    public function getLinkTuijian()
//    {
//        return $this->hasOne(self::)
//    }

    //所属门店
    public function getLinkAdmin()
    {
        return $this->hasOne(SysManager::className(),['id'=>'admin_id']);
    }

    //我的上级--推荐人
    public function getLinkUserUp()
    {
        return $this->hasOne(self::className(),['id'=>'tuijian_id']);
    }
    //我推荐的人
    public function getLinkChild()
    {
        return $this->hasMany(self::className(),['tuijian_id'=>'id']);
    }

    //我的上级--推荐人
    public function getLinkOrderCount()
    {
        return $this->hasOne(Order::className(),['uid'=>'id'])->select(['uid','order_num'=>'count(*)'])->groupBy('uid');
    }

    //用户收货地址
    public function getLinkRecAddr()
    {
        return $this->hasMany(UserAddr::className(),['uid'=>'id'])->orderBy('is_default desc,id desc');
    }
    //银行卡
    public function getLinkBankCard()
    {
        return $this->hasMany(UserBankCard::className(),['uid'=>'id']);
    }
    //用户消费记录
    public function getLinkUserLog()
    {
        return $this->hasMany(UserLog::className(),['uid'=>'id']);
    }

}
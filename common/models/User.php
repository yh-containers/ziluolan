<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class User extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%user}}';
    }


    //获取用户微信推广二维码
    public function getWechatQrcode()
    {
        $wechat_qrcode_img = $this->getAttribute('wechat_qrcode_img');
        if(empty($wechat_qrcode_img)){
            try{
                $wechat = \Yii::createObject(\Yii::$app->components['wechat']);
                //永久二维码
                list($ticket,$wechat_qrcode_img) = $wechat->qrcode($this->getAttribute('id'),'QR_LIMIT_SCENE');
                if(!empty($wechat_qrcode_img)){
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
     * 团队金额增加
     * @param double $number
     * */
    public function handleTeamWallet($number)
    {
        //增加团队金
        $group_user = $this->getAttribute('fl_uid_all');
        $group_user = empty($group_user)?[]:explode(',',$group_user);
        if($number>0 && !empty($group_user)){
            self::updateAllCounters(['team_wallet'=>$number],['id'=>$group_user]);
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
    public  function handleConsumWallet($number,$cond=false,$intro='',array $extra=[],$origin_type = 1,$is_group=0)
    {
        $quota = [$this->consum_wallet,$number];
        $this->updateCounters(['consum_wallet'=>$number]);
        array_push($quota,$this->consum_wallet);
        //记录日志
        UserLog::recordLog($this,2,$quota,$cond,$intro,$extra,$origin_type,$is_group);
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
        return $this->hasOne(self::className(),['tuijian_id'=>'id']);
    }

}
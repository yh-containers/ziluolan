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



    /**
     * 添加购物车
     * @param $gid int 商品id
     * @param $sku_id int 商品skuid
     * @param $num int 数量
     * @param $mod bool 调整数量
     * @return bool
     */
    public function addShoppingCart($gid,$sku_id,$num=1,$mod=false)
    {
        $bool = true;
        $model = UserCart::find()->where(['uid'=>$this->id,'gid'=>$gid])->one();
        if(!empty($model)){
            if($num<0 && $model->num<=1){

            }elseif($mod){
                $model->num=$num;
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


}
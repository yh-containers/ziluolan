<?php
namespace common\models;


class UserCart extends BaseModel
{
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%user_cart}}';
    }



    /**
     * 获取用户购物车数量
     * @var $user_id int 用户id
     * @return integer
     * */
    public static function getNum($user_id)
    {
        $num = self::find()->joinWith('linkGoods',false,'RIGHT JOIN')->where([
            'uid'=>$user_id,
            \common\models\Goods::tableName().'.status'=>1
        ])->sum('num');
        return $num?$num:0;
    }


    public function getLinkGoods()
    {
        return  $this->hasOne(Goods::className(),['id'=>'gid']);
    }

    public function getLinkSkuAttrPrice()
    {
        return  $this->hasOne(GoodsSkuAttrPrice::className(),['id'=>'sid']);
    }
}
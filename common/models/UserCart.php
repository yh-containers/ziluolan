<?php
namespace common\models;


class UserCart extends BaseModel
{
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%user_cart}}';
    }


    public function getLinkGoods()
    {
        return  $this->hasOne(Goods::className(),['id'=>'gid']);
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
}
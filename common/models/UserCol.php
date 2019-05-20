<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class UserCol extends BaseModel
{
    use SoftDelete;
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%user_col}}';
    }


    public function getLinkGoods()
    {
        return  $this->hasOne(Goods::className(),['id'=>'gid']);
    }
}
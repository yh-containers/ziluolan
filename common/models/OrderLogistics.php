<?php
namespace common\models;
use common\models\use_traits\SoftDelete;

class OrderLogistics extends BaseModel
{

    use SoftDelete;

    protected $use_create_time=false;
    public static function tableName()
    {
        return '{{%order_logistics}}';
    }


}

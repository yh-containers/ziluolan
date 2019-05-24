<?php
namespace common\models;

class OrderGoods extends BaseModel
{
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%order_goods}}';
    }



}
<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class UserWithdraw extends BaseModel
{
    use SoftDelete;
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%user_withdraw}}';
    }


}
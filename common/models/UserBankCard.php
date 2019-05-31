<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class UserBankCard extends BaseModel
{
    use SoftDelete;

    public static function tableName()
    {
        return '{{%user_bank_card}}';
    }

}
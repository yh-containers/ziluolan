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


}
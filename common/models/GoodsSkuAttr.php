<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class GoodsSkuAttr extends BaseModel
{
    public $use_create_time = false;
}
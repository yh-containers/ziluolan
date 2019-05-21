<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class GoodsSkuAttrPrice extends BaseModel
{
    public $use_create_time = false;

    public function getLinkGoods()
    {
        return $this->hasOne(Goods::className(),['id'=>'gid']);
    }
}
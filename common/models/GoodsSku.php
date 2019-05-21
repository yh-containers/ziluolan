<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class GoodsSku extends BaseModel
{
    public $use_create_time = false;
    //关联属性
    public function getLinkSkuAttr()
    {
        return $this->hasMany(GoodsSkuAttr::className(),['sku_id'=>'id']);
    }
}
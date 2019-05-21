<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class Goods extends BaseModel
{
    use SoftDelete;

    public static $fields_mode = [
        ['name'=>'普通商品'],
        ['name'=>'固定奖'],
        ['name'=>'推荐奖'],
        ['name'=>'团队奖'],
    ];


    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'n_id'       => '栏目',
            'name'       => '商品名',
            'mode'       => '分佣模式',
            'image'      => '封面图',
            'intro'      => '产品简介',
            'content'    => '详细内容',
        ]);
    }

    //商品封面图
    public static function getCoverImg($img)
    {
        $img = $img?explode(',',$img):null;
        return $img[0];
    }


    public function rules()
    {
        return [
            [['name','content'], 'required','message'=>'{attribute}必须输入'],
            [['image'], 'required','message'=>'{attribute}必须上传'],
            [['mode'], 'required','message'=>'{attribute}必须选择'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            ['sold_num','default','value'=>0],
            ['n_id','default','value'=>0],
            ['status','default','value'=>1],
            [['attr','content'], 'safe'],
        ];
    }

    //商品一件sku价格
    public function getLinkSkuAttrPriceOne()
    {
        return $this->hasOne(GoodsSkuAttrPrice::className(),['gid'=>'id'])->groupBy('gid')->orderBy('price asc');
    }
    public function getLinkSkuAttrPrice()
    {
        return $this->hasMany(GoodsSkuAttrPrice::className(),['gid'=>'id']);
    }

    //商品sku
    public function getLinkSku()
    {
        return $this->hasMany(GoodsSku::className(),['gid'=>'id']);
    }

}
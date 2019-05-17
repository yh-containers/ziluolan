<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class Ad extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%ad}}';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'name'      => '名称',
            'url'      => '链接',
            'image'      => '图片',
        ]);
    }



    public function rules()
    {
        return [
            [['image'], 'required','message'=>'{attribute}必须输入'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            ['status','default','value'=>1],
            //默认值
            ['type','default', 'value' => 0],
            ['lang','default', 'value' => 0],
            ['device','default', 'value' => 1],
            ['status','default', 'value' => 1],
            [['status','name','url','desc'], 'safe'],
        ];
    }



}
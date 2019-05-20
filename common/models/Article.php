<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class Article extends BaseModel
{
    use SoftDelete;

    public static $fields_is_up = ['否','是'];

    public static function tableName()
    {
        return '{{%article}}';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'cid'        => '文章分类',
            'title'      => '标题',
            'image'      => '图片',
            'content'    => '文章内容',
        ]);
    }



    public function rules()
    {
        return [
            [['cid'], 'required','message'=>'{attribute}必须选择'],
            [['title','content'], 'required','message'=>'{attribute}必须输入'],
            [['image'], 'required','message'=>'{attribute}必须上传'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            ['is_up','default','value'=>0],
            ['visit','default','value'=>0],
            ['status','default','value'=>1],
            [['relation_id','from','key','desc','addtime'], 'safe'],
        ];
    }


    public function getLinkNavPage()
    {
        return $this->hasOne(SysNavPage::className(),['id'=>'cid']);
    }

}
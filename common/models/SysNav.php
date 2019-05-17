<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class SysNav extends BaseModel
{
    use SoftDelete;

    public static function tableName()
    {
        return 'sys_nav';
    }


    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'name'      => '导航名称',
        ]);
    }


    public function rules()
    {
        return [
            [['name'], 'required','message'=>'{attribute}必须输入'],
            [['name'], 'string','length'=>[1,15],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            //默认值
            [['status'],'default', 'value' => 1],
            ['pid','default', 'value' => 0],
            ['url','default', 'value' => ''],
        ];
    }

    public function getLinkNav()
    {
        return $this->hasMany(self::className(),['pid'=>'id'])->orderBy('sort asc');
    }
}
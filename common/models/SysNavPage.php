<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class SysNavPage extends BaseModel
{
    use SoftDelete;

    public static $nav_prop = [
        ['name'=>'单页面','article_info'=>['type'=>'direct_detail']],
        ['name'=>'新闻','article_info'=>['type'=>'news','page_temp'=>'list']],
        ['name'=>'产品'],
        ['name'=>'案例','article_info'=>['type'=>'case']],
    ];

    public static function tableName()
    {
        return 'sys_nav_page';
    }


    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'name'      => '栏目名称',
            'route'     => '路由',
        ]);
    }


    public function rules()
    {
        return [
            [['name'], 'required','message'=>'{attribute}必须输入'],
            ['name', 'string','length'=>[1,15],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
//            ['route','match','pattern'=>'/^[0-9A-Za-z\/|&=-]+$/','message'=>'路由只支持字母、数字、|&=-'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            //默认值
            [['status'],'default', 'value' => 1],
            [['pid','type'],'default', 'value' => 0],
            [['key','desc','content','image','html','route'],'default', 'value' => ''],
        ];
    }

    public function getLinkNavPage()
    {
        return $this->hasMany(self::className(),['pid'=>'id'])->orderBy('sort asc');
    }
}
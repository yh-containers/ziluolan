<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class SysRole extends BaseModel
{
    use SoftDelete;

    public static function tableName()
    {
        return 'sys_role';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'pid'       => '角色等级',
            'name'      => '角色名',
            'node'      => '节点',
            'sort'      => '排序',
        ]);
    }

    //获取超级管理员用户列
    public static function getSupers()
    {
         $data= self::find()->asArray()->where(['pid'=>1,'status'=>1])->all();
         $supers=$data?array_column($data,'id'):[];
         array_push($supers,1);
         return $supers;
    }


    public function rules()
    {
        //默认路由
        switch ($this->scenario){
            default:
                $rules=[
                    [['name'],'required','message'=>'{attribute}必须输入'],
                    ['name','string','length'=>[1,25],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
                    ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
                    ['sort','default','value'=>100],
                    ['status','default','value'=>1],
                    ['pid','default','value'=>0],
                    ['node','default','value'=>''],
                ];
                break;
        }

        return $rules;
    }


    public function getLinkRoles()
    {
        return $this->hasMany(self::className(),['pid'=>'id'])->orderBy('sort asc');
    }
    /*
     * 获取角色上级
     * */
    public function getLinkParentRoles()
    {
        return $this->hasOne(self::className(),['id'=>'pid']);
    }

    /**
     * 门店角色
     * */
//    public function getLinkStoreRole()
//    {
//        return $this->hasMany(SysManager::className(),['rid'=>'id'])->where(['id'=>2]);
//    }
}
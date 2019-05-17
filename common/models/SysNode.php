<?php
namespace common\models;

class SysNode extends BaseModel
{
    public static function tableName()
    {
        return 'sys_node';
    }

    public function getLinkNode()
    {
        return $this->hasMany(SysNode::className(),['pid'=>'id'])->where(['!=','status',0])->orderBy('sort asc');
    }
}
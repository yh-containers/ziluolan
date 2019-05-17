<?php
namespace common\models;

class SysSetting extends BaseModel
{
    protected $use_create_time=false;

    public static function tableName()
    {
        return 'sys_setting';
    }
    public static function getTypeInfo($type=null,$field=null)
    {
        $data = [
            'normal'            => ['name' => '系统设置/基本资料/基本信息'],
            'recommend'         => ['name' => '系统设置/基本资料/推荐奖金'],
            'fixed'             => ['name' => '系统设置/基本资料/固定'],
            'group_award'       => ['name' => '系统设置/基本资料/团队奖金'],
            'protocol'          => ['name' => '系统设置/微商服务协议'],
        ];
        if(is_null($type)){
            return $data;
        }else{
            $type_info = isset($data[$type])?$data[$type]:[];

            if(is_null($field)){
                return $type_info;
            }else{
                return isset($type_info[$field])?$type_info[$field]:'';
            }
        }
    }


    public static function getContent($type)
    {
        $cache_name = 'setting_'.$type;
        $cache = \Yii::$app->cache;
        $data = $cache->getOrSet($cache_name, function ()use($type) {
            $data = self::findOne($type);
            return $data?$data['content']:'';
        });

        return $data;
    }


    public static function setContent($type,$content)
    {
        //删除缓存
        $cache_name = 'setting_'.$type;
        $cache = \Yii::$app->cache;
        $cache->delete($cache_name);
        $model = self::findOne($type);
        $model->content = $content;
        return $model->save();
    }

}
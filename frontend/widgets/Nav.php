<?php
namespace frontend\widgets;

use yii\base\Widget;

class Nav extends Widget
{
    const CACHE_NAME='nav_cache';
    public function run()
    {
        //清空缓存
//        \Yii::$app->cache->flush();

        $list = \Yii::$app->cache->getOrSet(self::CACHE_NAME,function(){
            $data = \common\models\SysNavPage::find()->asArray()->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
            foreach ($data as &$vo){
                $vo['url'] = self::defineRoute($vo['route']);
            }
            return $data;
        },60);
//        var_dump($list);exit;
        return $this->render('nav',[
            'list'=>$list,
        ]);
    }

    /**
     * 定义路由
     * @var string $dif_route
     * @return string|array
     * */
    public static function defineRoute($dif_route)
    {
        $route = '';
        if(preg_match('/^https?:\/\//',$dif_route)){
            $route = $dif_route;
        }else{
            $arr = $dif_route?explode('|',$dif_route):[];
        }

        if(!empty($arr)){
            $route = [$arr[0]];
            if(isset($arr[1])){
                //url参数
                $param = explode('&',$arr[1]);
                foreach ($param as $pm){
                    $slice = $pm?explode('=',$pm):[];
                    if(count($slice)==2){
                        //必须为key value形势
                        $route = array_merge($route,[$slice[0]=>$slice[1]]);
                    }
                }
            }
        }
        return $route;

    }
}
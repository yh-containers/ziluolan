<?php
namespace frontend\widgets;

use yii\base\Widget;

class Nav extends Widget
{
    const CACHE_NAME='nav_cache';
    public function run()
    {
        $list = \Yii::$app->cache->getOrSet(self::CACHE_NAME,function(){
            $data = \common\models\SysNavPage::find()->asArray()->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
            foreach ($data as &$vo){
                $arr = $vo['route']?explode('|',$vo['route']):[];
                $route = [$arr[0]];
//                var_dump($route);
                if(!empty($arr)){
                    if(isset($arr[1])){
                        //url参数
                        $param = explode('&',$arr[1]);
                        foreach ($param as $pm){
                            $slice = $pm?explode('=',$pm):[];
//                            var_dump([$slice[0]=>$slice[1]]);exit;
                            if(count($slice)==2){
                                //必须为key value形势
                                $route = array_merge($route,[$slice[0]=>$slice[1]]);
                            }
                        }
                    }
                }
                $vo['url'] = $route;
            }
            return $data;
        },60);
        return $this->render('nav',[
            'list'=>$list,
        ]);
    }
}
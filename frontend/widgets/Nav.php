<?php
namespace frontend\widgets;

use yii\base\Widget;

class Nav extends Widget
{
    const CACHE_NAME='nav_cache';
    public function run()
    {
        //清空缓存
        \Yii::$app->cache->flush();

        $list = \Yii::$app->cache->getOrSet(self::CACHE_NAME,function(){
            $data = \common\models\SysNavPage::find()->asArray()->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
            foreach ($data as &$vo){
                if(preg_match('/^https?:\/\//',$vo['route'])){
                    $route = $vo['route'];
                }else{
                    $arr = $vo['route']?explode('|',$vo['route']):[];
                }

//                var_dump($route);
                if(!empty($arr)){
                    $route = [$arr[0]];
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
//        var_dump($list);exit;
        return $this->render('nav',[
            'list'=>$list,
        ]);
    }
}
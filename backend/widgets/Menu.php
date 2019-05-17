<?php
namespace backend\widgets;

use yii\base\Widget;

class Menu extends Widget
{
    public $current_active;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $menu = \common\models\SysNode::find()->asArray()->with('linkNode.linkNode.linkNode')->where(['pid'=>0])->andWhere(['!=','status',0])->orderBy('sort asc')->all();

        $current_route = \Yii::$app->controller->getRoute();

        //上一个节点
        $up_node =[];
        foreach($menu as $key=>&$vo){
            if($vo['uri']==$current_route){
                $up_node[] = $vo['id'];
            }
            foreach ($vo['linkNode'] as $key=>&$item){
                if($item['uri']==$current_route) {
                    $up_node[] = $item['id'];
                    $up_node[] = $vo['id'];
                    if($item['status']==-1){
                        unset($vo['linkNode'][$key]);
                    }
                }else{
                    if($item['status']==-1){
                        unset($vo['linkNode'][$key]);
                    }
                }

                foreach($item['linkNode'] as $key=>&$node){
                    if($node['uri']==$current_route){
                        $up_node[] = $node['id'];
                        $up_node[] = $item['id'];
                        $up_node[] = $vo['id'];
                        if($node['status']==-1){
                            unset($item['linkNode'][$key]);
                        }
                    }else{
                        if($node['status']==-1){
                            unset($item['linkNode'][$key]);
                        }
                    }

                    foreach($node['linkNode'] as $key=>&$foo){
                        if($foo['uri']==$current_route){
                            $up_node[] = $foo['id'];
                            $up_node[] = $node['id'];
                            $up_node[] = $item['id'];
                            $up_node[] = $vo['id'];
                            if($foo['status']==-1){
                                unset($node['linkNode'][$key]);
                            }
                        }else{
                            if($foo['status']==-1){
                                unset($node['linkNode'][$key]);
                            }
                        }

                    }
                }

            }
        }
//        var_dump($menu);exit;
        return $this->render('menu',[
            'menu'=>$menu,
            'up_node' => $up_node
        ]);
    }
}
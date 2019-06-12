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
        $menu = \common\models\SysNode::find()->asArray()->andWhere(['!=','status',0])->orderBy('pid desc,sort asc')->all();
        //角色id不为1的均受权限控制
        if(\Yii::$app->controller->user_model['id']!=1){
            $menu_auth_data = []; //管理员角色权限
            $node = \Yii::$app->controller->user_model['linkRole']['node'];
            if(!empty($node)){
                $node =explode(',',$node);
                foreach ($menu as $vo){
                    if(!empty($vo['uri'])){
                        if(in_array($vo['uri'],$node)){
                            array_push($menu_auth_data,$vo);
                        }
                    }else{
                        array_push($menu_auth_data,$vo);
                    }
                }
            }

        }

        $menu = $this->_tree(isset($menu_auth_data)?$menu_auth_data:$menu,0);
        foreach($menu as $key=>&$vo){
            if(empty($vo['linkNode'])){
                unset($menu[$key]);
            }else{
                $temp_menu = $vo['linkNode'];
                foreach ($temp_menu as $ch_key=>$item){
                    if(empty($item['uri']) && empty($item['linkNode'])){
                        unset($temp_menu[$ch_key]);
                    }
                }
                $vo['linkNode']=$temp_menu;
            }

            if(empty($vo['linkNode'])){
                unset($menu[$key]);
            }

        }
        //上一个节点
        $current_route = \Yii::$app->controller->getRoute();
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

//        var_dump(json_encode($menu));
//        var_dump($menu);
//
//        var_dump($up_node);exit;

        return $this->render('menu',[
            'menu'=>$menu,
            'up_node' => $up_node
        ]);
    }


    private function _tree($array, $pid)
    {
        $tree = array();
        foreach ($array as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['linkNode'] = $this->_tree($array, $value['id']);
                $tree[] = $value;
            }
        }
        return $tree;
    }


//    public function run()
//    {
//        $menu = \common\models\SysNode::find()->asArray()->with('linkNode.linkNode.linkNode')->where(['pid'=>0])->andWhere(['!=','status',0])->orderBy('sort asc')->all();
//
//        $current_route = \Yii::$app->controller->getRoute();
//
//        //上一个节点
//        $up_node =[];
//        foreach($menu as $key=>&$vo){
//            if($vo['uri']==$current_route){
//                $up_node[] = $vo['id'];
//            }
//            foreach ($vo['linkNode'] as $key=>&$item){
//                if($item['uri']==$current_route) {
//                    $up_node[] = $item['id'];
//                    $up_node[] = $vo['id'];
//                    if($item['status']==-1){
//                        unset($vo['linkNode'][$key]);
//                    }
//                }else{
//                    if($item['status']==-1){
//                        unset($vo['linkNode'][$key]);
//                    }
//                }
//
//                foreach($item['linkNode'] as $key=>&$node){
//                    if($node['uri']==$current_route){
//                        $up_node[] = $node['id'];
//                        $up_node[] = $item['id'];
//                        $up_node[] = $vo['id'];
//                        if($node['status']==-1){
//                            unset($item['linkNode'][$key]);
//                        }
//                    }else{
//                        if($node['status']==-1){
//                            unset($item['linkNode'][$key]);
//                        }
//                    }
//
//                    foreach($node['linkNode'] as $key=>&$foo){
//                        if($foo['uri']==$current_route){
//                            $up_node[] = $foo['id'];
//                            $up_node[] = $node['id'];
//                            $up_node[] = $item['id'];
//                            $up_node[] = $vo['id'];
//                            if($foo['status']==-1){
//                                unset($node['linkNode'][$key]);
//                            }
//                        }else{
//                            if($foo['status']==-1){
//                                unset($node['linkNode'][$key]);
//                            }
//                        }
//
//                    }
//                }
//
//            }
//        }
////        var_dump($menu);exit;
//        return $this->render('menu',[
//            'menu'=>$menu,
//            'up_node' => $up_node
//        ]);
//    }
}
<?php
namespace frontend\components;

use yii\base\Action;

class ArticleAction extends Action
{
    //文章类型
    public $type = 'news';
    public $con_type = 0;  //筛选条件
    public $page_temp = '';




    public function run()
    {
        $con_type = $this->con_type;
        $id = \Yii::$app->request->get('id');
        $route_alias = \Yii::$app->request->get('route_alias');
        if($route_alias){
            //路由别名
            $model_route_nav = \common\models\SysNavPage::find()->where(['route_alias'=>$route_alias])->limit(1)->one();
            if(!empty($model_route_nav)){
                $id = $model_route_nav['id'];   //选中的哪项

                if(!empty($model_route_nav['pid'])){   //父级--层级关系只存在两级
                    $model_parent_route_nav = \common\models\SysNavPage::findOne($model_route_nav['pid']);
                    $con_type = $model_parent_route_nav['type'];
                }
            }
        }

        //获取案例所有栏目
        $menu = \common\models\SysNavPage::find()->with(['linkNavPage' => function ($query) {
            return $query->where(['status' => 1]);
        }])->asArray()->where(['pid' => 0, 'status' => 1, 'type' => $con_type])->one();

        //内容
        $content=$ch_title = '';
        $meta_key = $menu['key'];
        $meta_desc = $menu['desc'];
        $id = $id ? $id : (isset($menu['linkNavPage'][0]) ? $menu['linkNavPage'][0]['id'] : 0);
        if(empty($id) || $id==$menu['id']){
            $content = $menu['content'];
        }
        foreach ($menu['linkNavPage'] as $vo){
            if($vo['id']==$id){
                $content = $vo['content'];
                $ch_title = $vo['name'];
                $meta_key = $vo['key'];
                $meta_desc = $vo['desc'];
                break;
            }
        }


        return \Yii::$app->controller->render(empty($this->page_temp)?$this->type:$this->page_temp,[
            'title' => $menu['name'],
            'ch_title' => $ch_title,
            'id'=> $id,
            'con_type'=> $con_type,
            'meta_key'=>$meta_key,
            'meta_desc'=>$meta_desc,
            'menu'=>$menu,
            'content' => $content
        ]);
    }
}

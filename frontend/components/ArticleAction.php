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
        $id = \Yii::$app->request->get('id');

        //获取案例所有栏目
        $menu = \common\models\SysNavPage::find()->with(['linkNavPage' => function ($query) {
            return $query->where(['status' => 1]);
        }])->asArray()->where(['pid' => 0, 'status' => 1, 'type' => $this->con_type])->one();

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
            'con_type'=> $this->con_type,
            'meta_key'=>$meta_key,
            'meta_desc'=>$meta_desc,
            'menu'=>$menu,
            'content' => $content
        ]);
    }
}

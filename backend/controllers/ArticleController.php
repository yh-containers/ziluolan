<?php
namespace backend\controllers;


class ArticleController extends CommonController
{

    //文章管理
    public function actionIndex()
    {
        $query = \common\models\Article::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkNavPage'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('addtime desc')->all();
        return $this->render('index',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    //文章新增或删除
    public function actionAdd()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\Article();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $addtime = $this->request->post('addtime',date('Y-m-d H:i:s'));
            $php_input['addtime'] = strtotime($addtime);
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);

        //获取--新闻 菜单栏
        $nav = \common\models\SysNavPage::find()->with(['linkNavPage'=>function($query){
            return $query->where(['status'=>1,'type'=>1]);
        }])->where(['pid'=>0,'status'=>1,'type'=>1])->orderBy('sort asc')->all();

        return $this->render('add',[
            'model' => $model,
            'nav'   => $nav,
        ]);
    }


    //删除
    public function actionDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\Article();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

}

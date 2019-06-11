<?php
namespace backend\controllers;


class CarryController extends CommonController
{

    public function actionList()
    {
        //会员模型
        $query = \common\models\UserWithdraw::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query
            ->with(['linkUser'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy("status asc,id desc")
            ->all();

        return $this->render('list',[
            'list'  => $list,
            'pagination' => $pagination,
        ]);
    }

    //处理审核
    public function actionHandleAuth()
    {
        $id = $this->request->get('id');
        $state = $this->request->get('state');
        try{
            \common\models\UserWithdraw::handleAuth($id,$state);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        return $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }
}

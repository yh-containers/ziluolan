<?php
namespace frontend\controllers;

//商品列表
class GoodsController extends CommonController
{
    //商品分类
    public function actionCate()
    {
        //商品栏目
        $n_id = $this->request->get('n_id',0);
        //商品分类
        $model_cate = \common\models\SysNavPage::find()->where(['status'=>1,'type'=>2])->all();

        return $this->render('cate',[
            'n_id' => $n_id,
            'model_cate' => $model_cate,
        ]);
    }

    public function actionIndex()
    {
        return $this->actionCate();
    }

    //商品详情
    public function actionDetail()
    {
        $id = $this->request->get('id');
        $sku_id = $this->request->get('sku_id');
        $model = \common\models\Goods::find()->with(['linkSku'])->where(['id'=>$id])->one();
        return $this->render('detail',[
            'model' =>$model,
            'sku_id' =>$sku_id,
        ]);
    }




    //商品列表
    public function actionShowList()
    {
        //商品栏目(分类)
        $n_id = $this->request->get('n_id');

        $query = \common\models\Goods::find()->with(['linkSkuOne'])->where(['status'=>1]);
        !empty($n_id) && $query->andWhere(['n_id'=>$n_id]);

        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query
            ->asArray()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('sort desc')
            ->all();

        $data = [];

        foreach($list as $vo){
            $info = [
                'id'         =>  $vo['id'],
                'sku_id'     =>  $vo['linkSkuOne']['id'],
                'name'       =>  $vo['name'],
                'cover_img'  =>  \common\models\Goods::getCoverImg($vo['image']),
                'sku_price'  =>  empty($vo['linkSkuOne']['price'])?0.00:$vo['linkSkuOne']['price'],
            ];

            $data[] = $info;
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }
}
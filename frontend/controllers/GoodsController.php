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
        $get_sku_id = $this->request->get('sku_id');
        $model = \common\models\Goods::find()->with(['linkSku.linkSkuAttr','linkSkuAttrPrice'])->where(['id'=>$id])->one();
        //组织商品sku属性
        $sku = $sku_table =$sku_temp_table =$sku_choose_info = [];
        foreach ($model['linkSku'] as $vo){
            $info=[
                'id' => $vo['id'],
                'temp_id' => $vo['id'],
                'name'=> $vo['name'],
                'attr'=>[],
            ];
            foreach ($vo['linkSkuAttr'] as $attr){
                $info['attr'][] = [
                    'id' => $attr['id'],
                    'temp_id' => $attr['id'],
                    'name' => $attr['name'],
                ];
                $sku_temp_table[$attr['id']]=$attr['name'];
            }
            $sku[] = $info;
        }
        //商品价格
        foreach ($model['linkSkuAttrPrice'] as $vo){
            !isset($temp_sku_id) && $temp_sku_id = $vo['id'];
            if($get_sku_id == $vo['id']){
                $sku_id = $vo['id'];
            }
            $attr = [];
            $sku_group = empty($vo['sku_group'])?[]:explode('|',$vo['sku_group']);
            $sku_choose_info_name = [];
            foreach ($sku_group as $g_vo){
                $name = isset($sku_temp_table[$g_vo]) ? $sku_temp_table[$g_vo] : '';
                $attr[] =[
                    'id' => $vo['id'],
                    'temp_id' => $g_vo,
                    'name' => $name,
                ];
                $sku_choose_info_name []= $name;

            }
            $sku_choose_info[$vo['id']] = [
                'name'      => implode('|',$sku_choose_info_name),
                'sku_group' => $vo['sku_group'],
                'price'     => $vo['price'],
            ];

        }

        return $this->render('detail',[
            'model' =>$model,
            'sku_id' => isset($sku_id)?$sku_id:(isset($temp_sku_id)?$temp_sku_id:0),
            'sku' => $sku,
            'sku_choose_info' => $sku_choose_info,
        ]);
    }




    //商品列表
    public function actionShowList()
    {
        //商品栏目(分类)
        $n_id = $this->request->get('n_id');

        $query = \common\models\Goods::find()->with(['linkSkuAttrPriceOne'])->where(['status'=>1]);
        !empty($n_id) && $query->andWhere(['n_id'=>$n_id]);

        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query
            ->asArray()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('sort asc')
            ->all();

        $data = [];

        foreach($list as $vo){
            $info = [
                'id'         =>  $vo['id'],
                'sku_id'     =>  $vo['linkSkuAttrPriceOne']['id'],
                'name'       =>  $vo['name'],
                'cover_img'  =>  \common\models\Goods::getCoverImg($vo['image']),
                'sku_price'  =>  empty($vo['linkSkuAttrPriceOne']['price'])?0.00:$vo['linkSkuAttrPriceOne']['price'],
            ];

            $data[] = $info;
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'pages'=>$pagination->pageCount]);
    }
}
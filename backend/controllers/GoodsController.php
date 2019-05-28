<?php
namespace backend\controllers;


class GoodsController extends CommonController
{
    //产品
    public function actionIndex()
    {
        $query = \common\models\Goods::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkSku.linkSkuAttr','linkSkuAttrPriceOne'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();
        return $this->render('index',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    //新增或编辑
    public function actionAdd()
    {
        $id = $this->request->isGet?$this->request->get('id',0):$this->request->post('id',0);
        $model = new \common\models\Goods();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $image = $this->request->post('image');
            $goods_sku = $this->request->post('sku');
            $goods_sku_table = $this->request->post('sku_table');
//            var_dump($php_input);exit;
            $php_input['image'] = empty($image)?'':implode(',',$image);//商品图片
            if(empty($goods_sku_table))  throw new \yii\base\UserException('请设置商品sku属性');

            //table_sku验证
            foreach ($goods_sku_table as $vo){
                if(empty($vo['attr'])) throw new \yii\base\UserException('提交数据异常,请刷新页面重试');
                if(empty($vo['info'])) throw new \yii\base\UserException('请设置商品sku价格等信息');
                $table_attr =$vo['attr'];
                $info = $vo['info'];
                if(empty($info['price'])) throw new \yii\base\UserException('请设置商品sku价格');
                if($info['price']<=0) throw new \yii\base\UserException('sku价格必须大于0');
            }




            if($id){
                $model = \common\models\Goods::findOne($id);
                if(empty($model))  throw new \yii\base\UserException('编辑的商品不存在');
            }


            $model->attributes = $php_input;
            $model->validate();
            if($model->hasErrors()){
                $error_msg = $model->getFirstErrors();
                throw new \yii\base\UserException($error_msg[key($error_msg)]);
            }

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //编辑状态
                if (!empty($id) && !empty($goods_sku_id)) {
                    \common\models\GoodsSku::deleteAll(['and', ['gid' => $id], ['not in', 'id', $goods_sku_id]]);
                }

                $model->save(false);

                //商品sku属性
                $goods_sku_exist_id = $goods_sku_attr_exist_id =$goods_sku_attr_price_exist_id = $goods_sku_attr_data = [];
                foreach ($goods_sku as $gs_key=>$vo){
                    $name = empty($vo['name'])?'':$vo['name'];
                    $model_goods_sku = \common\models\GoodsSku::find()->where(['name'=>$name,'gid'=>$model->id])->one();
                    if(empty($model_goods_sku)){
                        //商品sku
                        $model_goods_sku = new \common\models\GoodsSku();
                        //sku名称
                        $model_goods_sku->name = empty($vo['name'])?'':$vo['name'];
                        $model_goods_sku->gid = $model->id;
                        $model_goods_sku->save(false);
                    }
                    //编辑记录已存在的数据
                    !empty($id) && $goods_sku_exist_id[] = $model_goods_sku->id;

//                    $goods_sku_table
                    if(!empty($vo['attr']) && is_array($vo['attr'])){
                        //商品sku_attr
                        foreach ($vo['attr'] as $attr_key => $attr) {
                            $attr_name = empty($attr['name'])?'':$attr['name'];
                            $model_goods_sku_attr = \common\models\GoodsSkuAttr::find()->where(['name'=>$attr_name,'gid'=>$model->id])->one();

                            if(empty($model_goods_sku_attr)){
                                $model_goods_sku_attr = new \common\models\GoodsSkuAttr();
                                $model_goods_sku_attr->sku_id = $model_goods_sku->id;
                                $model_goods_sku_attr->gid = $model->id;
                                $model_goods_sku_attr->name = $attr_name;
                                $model_goods_sku_attr->save(false);
                            }
                            //编辑记录已存在的数据
                            !empty($id) && $goods_sku_attr_exist_id[] = $model_goods_sku_attr->id;

                            //临时id
                            $temp_id = $attr['temp_id'];
                            $goods_sku_attr_data[$temp_id] = $model_goods_sku_attr['id'];
                        }
                    }
                }

                //价格数据
                foreach ($goods_sku_table as $vo){


                    $price_attr = empty($vo['attr'])?[]:$vo['attr'];
                    $price_info = empty($vo['info'])?[]:$vo['info'];
                    $sku_group = [];
                    foreach ($price_attr as $pa){
                        //临时id
                        $temp_id = $pa['temp_id'];
                        $sku_group[]=isset($goods_sku_attr_data[$temp_id])?$goods_sku_attr_data[$temp_id]:0;
                        //排序
                        sort($sku_group);
                    }
                    $sku_group_implode =implode('|', $sku_group);
                    $model_goods_sku_attr_price = \common\models\GoodsSkuAttrPrice::find()->where(['gid'=>$model->id,'sku_group'=>$sku_group_implode])->one();
                    if(empty($model_goods_sku_attr_price)){
                        $model_goods_sku_attr_price = new \common\models\GoodsSkuAttrPrice();
                    }


                    $model_goods_sku_attr_price->sku_group = $sku_group_implode;
                    $model_goods_sku_attr_price->gid = $model->id;
                    $model_goods_sku_attr_price->price = empty($price_info['price'])?0.00:$price_info['price'];
                    $model_goods_sku_attr_price->stock = empty($price_info['stock'])?0.00:$price_info['stock'];
                    $model_goods_sku_attr_price->save(false);

                    //编辑记录已存在的数据
                    !empty($id) && $goods_sku_attr_price_exist_id[] = $model_goods_sku_attr_price->id;
                }
                //删除移除的数据
                !empty($goods_sku_exist_id) && \common\models\GoodsSku::deleteAll(['and',['gid'=>$model->id],['not in','id',$goods_sku_exist_id]]);
                !empty($goods_sku_attr_exist_id) && \common\models\GoodsSkuAttr::deleteAll(['and',['gid'=>$model->id],['not in','id',$goods_sku_attr_exist_id]]);
                !empty($goods_sku_attr_price_exist_id) && \common\models\GoodsSkuAttrPrice::deleteAll(['and',['gid'=>$model->id],['not in','id',$goods_sku_attr_price_exist_id]]);

                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
                throw new \yii\base\UserException($e->getMessage());
            }




            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }
        $model = $model::find()->with(['linkSku.linkSkuAttr','linkSkuAttrPrice'])->where(['id'=>$id])->one();
        $sku = $sku_table =$sku_temp_table = [];
        if(!empty($model['linkSku']) && is_array($model['linkSku'])){
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
        }

        if(!empty($model['linkSkuAttrPrice']) && is_array($model['linkSkuAttrPrice'])){
            foreach ($model['linkSkuAttrPrice'] as $vo){
                $attr = [];
                $sku_group = empty($vo['sku_group'])?[]:explode('|',$vo['sku_group']);
                foreach ($sku_group as $g_vo){
                    $attr[] =[
                        'id' => $vo['id'],
                        'temp_id' => $g_vo,
                        'name' => isset($sku_temp_table[$g_vo]) ? $sku_temp_table[$g_vo] : '',
                    ];
                }

                $sku_table[]=[
                    'attr' => $attr,
                    'info' => [
                        'price' =>$vo['price'],
                        'stock' =>$vo['stock'],
                    ]
                ];
            }
        }
//        var_dump($sku_table);exit;
        //获取--新闻 菜单栏
        $nav = \common\models\SysNavPage::find()->with(['linkNavPage'=>function($query){
            return $query->where(['status'=>1,'type'=>2]);
        }])->where(['pid'=>0,'status'=>1,'type'=>2])->orderBy('sort asc')->all();

        return $this->render('add',[
            'model' => $model,
            'sku' => $sku,
            'sku_table' => $sku_table,
            'nav'   => $nav,
        ]);
    }


    //删除
    public function actionDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\Goods();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

}

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
        $list = $query->with(['linkSku'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();
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
            $php_input['image'] = empty($image)?'':implode(',',$image);//商品图片

            $goods_sku_id = $this->request->post('sku_id');
            $goods_sku_name = $this->request->post('sku_name');
            $goods_sku_stock = $this->request->post('sku_stock');
            $goods_sku_price = $this->request->post('sku_price');
            if(!is_array($goods_sku_price)){
                $goods_sku_price = [(float)$goods_sku_price];
            }

            if($id){
                $model = \common\models\Goods::findOne($id);
                if(empty($model))  throw new \yii\base\UserException('编辑的商品不存在');
            }

            foreach ($goods_sku_price as $vo){
                if(empty($vo) || $vo<0 || !is_numeric($vo)){
                    throw new \yii\base\UserException('商品价格必须输入');
                }
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
                //sku入库字段顺序
                $goods_sku_insert_fields = ['gid', 'price', 'name', 'stock'];
                $goods_sku_data = [];
                foreach ($goods_sku_price as $key => $vo) {
                    $sku_info = [
                        'gid'=>$model->id,
                        'price'=>isset($goods_sku_price[$key]) ? $goods_sku_price[$key] : 0.00,
                        'name'=>isset($goods_sku_name[$key]) ? $goods_sku_name[$key] : '',
                        'stock'=>isset($goods_sku_stock[$key]) ? $goods_sku_stock[$key] : 0,
                    ];
                    //编辑状态
                    if (isset($goods_sku_id[$key])) {
                        \common\models\GoodsSku::updateAll($sku_info, ['id' => $goods_sku_id[$key]]);
                    }else{
                        $goods_sku_data[] = $sku_info;
                    }

                }
                if ($goods_sku_data) {
                    //执行批量添加
                    \Yii::$app->db->createCommand()->batchInsert(\common\models\GoodsSku::tableName(), $goods_sku_insert_fields, $goods_sku_data)->execute();
                }
                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
                throw new \yii\base\UserException($e->getMessage());
            }

            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }
        $model = $model::findOne($id);
        $goods_sku_data = [];
        !empty($model) && $goods_sku_data = $model->linkSku;
        //获取--新闻 菜单栏
        $nav = \common\models\SysNavPage::find()->with(['linkNavPage'=>function($query){
            return $query->where(['status'=>1,'type'=>2]);
        }])->where(['pid'=>0,'status'=>1,'type'=>2])->orderBy('sort asc')->all();

        return $this->render('add',[
            'model' => $model,
            'goods_sku_data' => $goods_sku_data,
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

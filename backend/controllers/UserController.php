<?php
namespace backend\controllers;


class UserController extends CommonController
{

    public function actionIndex()
    {
        $id = $this->request->get('id');
        $dengji = $this->request->get('dengji');
        $sou = trim($this->request->get('sou'));
        $team_wallet = $this->request->get('team_wallet');
        $admin_id = $this->request->get('admin_id');
        //会员模型
        $query = \common\models\User::find();

        if($id){
            $query->andWhere(['id'=>$id]);
        }

        if($dengji){
            if($dengji=='C'){
                $query->andWhere(['between','integral',2,99]);
            }elseif($dengji=='P'){
                $query->andWhere(['between','integral',100,299]);
            }elseif($dengji=='S'){
                $query->andWhere(['>','integral',299]);
            }
        }

        if($team_wallet){
            if($team_wallet==-1){
                $query->andWhere(['>','team_wallet',0]);
            }elseif($team_wallet==1){
                $query->andWhere(['between','team_wallet',0,1000]);
            }elseif($team_wallet==2){
                $query->andWhere(['between','team_wallet',1000,10000]);
            }elseif($team_wallet==3){
                $query->andWhere(['>','team_wallet',10000]);
            }
        }

        if($sou){
            $query->andWhere(['or',['like','username',$sou],['like','number',$sou]]);
        }

        if($admin_id){
            $query->andWhere(['admin_id'=>$admin_id]);
        }


        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query
            ->with(['linkAdmin','linkUserUp'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy("id desc")
            ->all();


        return $this->render('index',[
            'list'  => $list,
            'pagination' => $pagination,
        ]);
    }

    public function actionDetail()
    {
        $id = $this->request->get('id');
        $model = \common\models\Member::findOne($id);
        return  $this->render('detail',[
            'model'=>$model
        ]);
    }


    //修改推荐人
    public function actionModTj()
    {
        $id = $this->request->post('send_id');
        $name = trim($this->request->post('sp_name'));
        $type= $this->request->post('type');
        $bool = false;
        if($type=='1'){
            //当前操作用户
            $model_opt_member = \common\models\Member::findOne($id);
            if(empty($model_opt_member))  throw new \yii\base\UserException('操作对象异常');
            if($model_opt_member['number']==$name) throw new \yii\base\UserException('新推荐人会员号不能与原推荐会员号相同');

            $model_member = \common\models\Member::find()->asArray()->where(['number'=>$name])->one();
            if(empty($model_member)) throw new \yii\base\UserException('新推荐人会员号不存在');

            if($model_member['tuijian_id']==$id)  throw new \yii\base\UserException('禁止设置互为推荐人');
            $model_opt_member->tuijian_id = $model_member['id'];
            $model_opt_member->admin_id = $model_member['admin_id'];
            $bool = $model_opt_member->save();

        }elseif ($type=='2') {
            //当前操作用户
            $model_opt_member = \common\models\Member::findOne($id);
            if(empty($model_opt_member))  throw new \yii\base\UserException('操作对象异常');
            if($model_opt_member['number']==$name) throw new \yii\base\UserException('新推荐人会员号不能与原推荐会员号相同');

            $model_member = \common\models\Member::find()->where(['number'=>$name])->one();
            if(empty($model_member)) throw new \yii\base\UserException('新推荐人会员号不存在');

            if($model_member['tuijian_id']==$id)  throw new \yii\base\UserException('禁止设置互为推荐人');

            $model_opt_member->direct_id = $model_member['id'];
            $model_opt_member->admin_id = $model_member['admin_id'];
            $bool = $model_opt_member->save();
        }
        return $this->asJson(["code"=>(int)$bool,"msg"=>$bool?'修改成功':'修改失败']);
    }

    //订单列表
    public function actionOrderList()
    {
        $id = $this->request->get('id');
        $state = $this->request->get('state');
        $sou = trim($this->request->get('sou'));
        $model = \common\models\Member::findOne($id);

        //模型
        $query = \common\models\OrderList::find()->where(['member_id'=>$id]);
        if(!empty($state)){
            if($state==9){
                $query->andWhere(['state'=>0]);
            }elseif ($state==2){
                $query->andWhere(['state'=>[2,3]]);
            }else{
                $query->andWhere(['state'=>$state]);
            }
        }
        !empty($sou) && $query= $query->andWhere(['like','sn',$sou]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $query = $query
            ->joinWith(['linkMember'],false)
            ->offset($pagination->offset)->limit($pagination->limit)->orderBy("id desc");
        $list = [];
        foreach ($query->each() as $item){
            $cart_id_arr=$item['cart_id']?explode('|',$item['cart_id']):[];
            $cart_info=\common\models\Cart::find()->with(['linkProduct'])->where(['id'=>$cart_id_arr])->all();
            $cart_base_info =[];
            foreach ($cart_info as $ci){
                $cart_base_info[]=[
                    'goods_id' => $ci['linkProduct']['id'],
                    'goods_name' => $ci['linkProduct']['name'],
                    'shop_type' => $ci['shop_type'],
                    'price' => $ci['price'],
                    'num' => $ci['num'],
                    'guige' => $ci['guige'],
                ];
            }
            $item['cart_base_info']=$cart_base_info;
            $list[] = $item;
        }
        return $this->render('orderList',[
            'prices' => \common\models\OrderList::find()->where(['member_id'=>$id])->sum('prices'),
            'state'=>$state,
            'sou'=>$sou,
            'model'=>$model,
            'list'  => $list,
            'pagination' => $pagination,
        ]);
    }


}

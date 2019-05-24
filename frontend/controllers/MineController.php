<?php
namespace frontend\controllers;


use yii\db\Expression;

class MineController extends CommonController
{

    public $is_need_login=true;
    /**
     * 用户模型
     * @var \common\models\User
     * */
    protected $user_model;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->user_model = \common\models\User::findOne($this->user_id);
        if(empty($this->user_model)){
            $this->user_id = 0;
        }
    }

    public function actionIndex()
    {

        return $this->render('index',[
            'user_model' => $this->user_model
        ]);
    }

    //个人中心
    public function actionInfo()
    {
        if($this->request->isAjax){
            $limit_field = ['usersname'=>'姓名','username'=>'昵称','sex'=>'','birthday'=>'生日','address'=>'地址','weixin'=>'','phone'=>'手机号码'];
            $php_input = $this->request->post();
            foreach($limit_field as $field=>$tip_msg){
                if(empty($php_input[$field]) && !empty($tip_msg)){
                    throw new \yii\base\UserException($tip_msg.'必须填写');
                }elseif(isset($php_input[$field])){
                    $this->user_model[$field]= $php_input[$field];
                }
            }
            $bool = $this->user_model->save(false);
            if($bool){
                return $this->asJson(['code'=>1,'msg'=>'操作成功']);
            }else{
                return $this->asJson(['code'=>0,'msg'=>'操作失败']);
            }

        }

        return $this->render('info',[
            'user_model' => $this->user_model
        ]);
    }

    //购物车列表
    public function actionCart()
    {
        $list = \common\models\UserCart::find()
            ->asArray()
            ->joinWith(['linkGoods'])
            ->with(['linkSkuAttrPrice'])
            ->all();

        foreach ($list as &$vo){
            $attr_name = '';
            if($vo['linkSkuAttrPrice']['sku_group']){
                $attrs = explode('|',$vo['linkSkuAttrPrice']['sku_group']);
                $model_sku_attr = \common\models\GoodsSkuAttr::find()->asArray()->where(['id'=>$attrs])->all();
                $model_sku_attr_name = array_column($model_sku_attr,'name');
                $attr_name = implode('/',$model_sku_attr_name);
            }
            $vo['attr_name'] = $attr_name;
        }


        return $this->render('cart',[
            'list' => $list
        ]);
    }


    //添加购物车
    public function actionAddCart()
    {
        $gid = $this->request->get('gid');
        $sku_id = $this->request->get('sku_id');
        $num = $this->request->get('num',1);
        $num_step = $this->request->get('num_step',0);
        $mod = $this->request->get('mod',0);
        if(empty($gid)) throw new \yii\base\UserException('请求信息异常:id');
//        if(empty($sku_id)) throw new \yii\base\UserException('请求信息异常:sku_id');

        $bool = $this->user_model->addShoppingCart($gid,$sku_id,$num,$mod,$num_step);
        //绑定购物车数量
        $cart_num = \common\models\UserCart::getNum($this->user_id);
        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>'加入购物车成功','cart_num'=>$cart_num]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'加入购物车失败']);
        }
    }

    //商品收藏
    public function actionGoodsCol()
    {
        $gid = $this->request->get('gid');
        $is_del = $this->request->get('is_del');
        $is_del==1?true:false;
        if(empty($gid)) throw new \yii\base\UserException('请求信息异常');
        try{
            list($bool,$is_col) = $this->user_model->goodsCol($gid,$is_del);
        }catch (\Exception $e){
            return  $this->asJson(['code'=>0,'msg'=>'操作异常:'.$e->getMessage()]);
        }

        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>!$is_del?($is_col?'收藏成功':'已取消收藏'):'已取消收藏','is_col'=>$is_col]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'收藏失败']);
        }
    }

    //购物车商品选中/取消选中效果
    public function actionCartChoose()
    {
        $cart_id = $this->request->get('cart_id');
        $is_checked = $this->request->get('is_checked');
        if(empty($cart_id) && $is_checked=='') throw new \yii\base\UserException('请求信息异常');
        list($bool,$is_checked) = $this->user_model->cartChoose($cart_id,$is_checked==''?null:$is_checked);


        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>'操作成功','is_checked'=>$is_checked]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'收藏失败']);
        }
    }

    //删除购物车
    public function actionCartDel()
    {
        $c_ids = $this->request->get('c_ids');
        $c_ids = is_array($c_ids)?$c_ids:($c_ids?explode(',',$c_ids):[]);
        if(empty($c_ids)) throw new \yii\base\UserException('请求信息异常');

        try{
            $this->user_model->cartDel($c_ids);
            //绑定购物车数量
            $cart_num = \common\models\UserCart::getNum($this->user_id);
            return  $this->asJson(['code'=>1,'msg'=>'操作成功','cart_num'=>$cart_num]);
        }catch (\Exception $e) {
            return  $this->asJson(['code'=>0,'msg'=>'操作失败']);
        }

    }



    //我的仓库
    public function actionWarehouse()
    {
        $state = $this->request->get('state',0);

        return $this->render('warehouse',[
            'state' => $state,
        ]);
    }


    //我的地址
    public function actionAddress()
    {
        $channel = $this->request->get('channel');
        return $this->render('address',[
            'channel' => $channel
        ]);
    }
    //我的地址-列表
    public function actionAddressList()
    {
        $query = \common\models\UserAddr::find()
            ->where([
                'uid'=>$this->user_id
            ]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('is_default desc,id desc')
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'username'   =>  $vo['username'],
                'phone'      =>  substr_replace($vo['phone'],'****',3,4),
                'is_default' => $vo['is_default'],
                'addr'       => $vo['addr'],
                'addr_extra' => $vo['addr_extra'],
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }

    //我的地址-新增/编辑
    public function actionAddressAdd()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\UserAddr();
        if($this->request->isAjax){
            $is_default = $this->request->post('is_default');
            $php_input = $this->request->post();
            $php_input['uid'] = $this->user_id;
            $php_input['is_default'] = empty($is_default)?0:1;
            $model->scenario = \common\models\UserAddr::SCENARIO_OPT_DATA;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);

        return $this->render('addressAdd',[
            'model'=>$model
        ]);
    }

    //删除地址
    public function actionAddressDel()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\UserAddr();
        $result = $model->actionDel(['id'=>$id,'uid'=>$this->user_id]);

        return $this->asJson($result);
    }

    //用户提现
    public function actionWithdraw()
    {

        return $this->render('withdraw',[

        ]);
    }

    //分享
    public function actionShare()
    {
        return $this->render('share',[
            'user_model'  => $this->user_model,
            'wechat_qrcode_img'  => $this->user_model->getWechatQrcode(),
        ]);
    }

    //推荐人
    public function actionReferee()
    {
        //队伍链
        $fl_uid_all = $this->user_model['fl_uid_all'];
        $fl_uid_all = empty($fl_uid_all)?[]:explode(',',$fl_uid_all);
        //直推人
        $mine_up = isset($fl_uid_all[0])?\common\models\User::findOne($fl_uid_all[0]):null;

        //下级
        $link_users = \common\models\User::find()
            ->where(new \yii\db\Expression("find_in_set(:USER_ID,fl_uid_all)=:LEVEL",[":USER_ID"=>"$this->user_id",":LEVEL"=>"1"]))
            ->all();
        return $this->render('referee',[
            'user_model'  => $this->user_model,
            'mine_up'     => $mine_up,
            'link_users'  => $link_users,
        ]);
    }
}
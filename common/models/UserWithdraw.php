<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class UserWithdraw extends BaseModel
{
    use SoftDelete;
    public static $fields_status=['申请中','通过','拒绝'];
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%user_withdraw}}';
    }

    /**
     * 提现审核
     * @param int $id 提现id
     * @param int $state 提现状态
     * @throws
     *
     * */
    public static function handleAuth($id,$state)
    {
        if(empty($id))  throw new \Exception('信息异常:id');
        if(empty($state))  throw new \Exception('信息异常:state');

        $model = self::findOne($id);
        if(empty($model))   throw new \Exception('数据不存在');
        if(!empty($model['status']))   throw new \Exception('数据位处于待审核状态');

        if($state==1){
            //通过
        }elseif ($state==2){
            //拒绝
            $model_user = User::findOne($model['uid']);
            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            if(!empty($model_user)){
                //返还用户
                $model_user->handleWallet($model['in_money'],$id,'提现被拒,返还:'.$model['in_money'],[],0,0,10);
            }
        }else{
            throw new \Exception('处理状态异常:state_'.$state);
        }

        try{
            $date_time = date('Y-m-d H:i:s');
            $model['status'] = $state;
            $model['auth_time'] = $date_time;
            $model['complete_time'] = $date_time;
            $model->save(false);
            isset($transaction) && $transaction->commit();
        }catch (\Exception $e){
            isset($transaction) && $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    public function getLinkUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }

}
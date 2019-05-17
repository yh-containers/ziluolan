<?php
namespace common\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{

    protected $use_create_time = true;

    //日志字段
    public static $opt_log_intro;
    public static $opt_log_intro_extra=[];


    public static $fields_status = ['异常','正常','禁用'];

    //更新时间
    public function getUpdateTime()
    {
        return $this->update_time?date('Y-m-d H:i:s',$this->update_time):'--';
    }
    //创建时间
    public function getCreateTime()
    {
        return $this->create_time?date('Y-m-d H:i:s',$this->create_time):'--';
    }

    //状态
    public static function getPropInfo($propOrFunc,$key=null,$field=null)
    {
        $class = self::className();
        if(property_exists(self::className(),$propOrFunc)){
            $data = $class::$$propOrFunc;
        }elseif(method_exists(self::className(),$propOrFunc)){
            $data = $class::$propOrFunc();
        }else{
            return false;
        }

        if(is_null($key)){
            return $data;
        }else{
            $info = isset($data[$key])?$data[$key]:[];
            return is_null($field)?$info:(isset($info[$field])?$info[$field]:'');
        }
    }

    /**
     * 自动添加时间戳，序列化参数
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if($this->use_create_time){
            $behaviors[]=[
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['create_time','update_time'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time']
                ],
            ];
        }

        return $behaviors;
    }

    /*
     * 保存数据
     * */
    public function actionSave($php_input)
    {
        $model = $this;
        $primary_keys = $model->primaryKey();
        $primary_keys_one_filed = isset($primary_keys[0])?$primary_keys[0]:'';
        if($primary_keys_one_filed && !empty($php_input[$primary_keys_one_filed])){
            $model = $model->findOne($php_input[$primary_keys_one_filed]);
            $model->scenario = $this->scenario;
            if(empty($model)){
                return ['code'=>0,'msg'=>'操作对象异常'];
            }
        }
        $model->attributes = $php_input;

        $state = $model->save();
        if(!$model->hasErrors()){
            return ['code'=>$state?1:0,'msg'=>$state?'操作成功':'操作失败'];
        }else{
            $error_msg = $model->getFirstErrors();
            return ['code'=>0,'msg'=>$error_msg[key($error_msg)]];
        }
    }

    /*
     * 删除数据
     * */
    public function actionDel(array $where)
    {
        $model = self::find()->where($where)->one();
        if(empty($model)){
            return ['code'=>0,'数据不存在或已删除!'];
        }
        $state = $model->delete();
        if($state) {
            return ['code'=>1,'msg'=>'删除成功'];
        }else{
            return ['code'=>0,'msg'=>'删除异常'];
        }
    }


}
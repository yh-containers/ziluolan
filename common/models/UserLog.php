<?php
namespace common\models;


class UserLog extends BaseModel
{
    const TYPE_DEFAULT = 0;//默认类型
    protected $use_create_time=false;
    public static $fields_type = [
        ['name'=>'其它',],
        ['name'=>'健康豆','field'=>'deposit_money'],
        ['name'=>'消费金豆','field'=>'consum_wallet'],
        ['name'=>'团队提成','field'=>'team_wallet'],
    ];

    public static $fields_origin_type = [
        ['name'=>'其它'],
        ['name'=>'订单'],
    ];

    public static function tableName()
    {
        return '{{%user_log}}';
    }

    /**
     * 记录日志
     * @param BaseModel $model 操作模型
     * @param int $type 日志类型
     * @param int|array|bool $quota //变动额度
     * @param bool|int //变动的条件-关联条件
     * @param string 说明
     * @param array 拓展数据
     * @param int $origin_type 来源类型 0其它 1订单
     * @param int $is_group 是否为团队奖励
     *
     * */
    public static function recordLog(BaseModel $model,$type=0,$quota=false,$cond=false,$intro='',array $extra=[],$origin_type=0,$is_group=0)
    {
        $model_log = new self();
        if($model instanceof User){
            $model_log->uid = $model->getAttribute('id');
        }
        //数据变动信息
        if(is_array($quota) && count($quota)==3){
            $model_log->befor_quota=$quota[0];
            $model_log->quota=$quota[1];
            $model_log->after_quota=$quota[2];

        }elseif (!is_array($quota)){
            $model_log->quota=$quota;

        }elseif ($quota!==false){
            array_push($extra,['quota'=>$quota]);

        }

        $model_log->type = $type;
        $cond!==false && $model_log->cond = $cond;
        $model_log->origin_type = $origin_type; //日志来源类型
        $model_log->is_group = $is_group?1:0; //是否团队奖励
        $model_log->intro = $intro;
        !empty($extra) && $model_log->extra = json_encode($extra);
        $model_log->create_time = date('Y-m-d H:i:s');
        $model_log->save(false);
    }


    public function getLinkUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
}
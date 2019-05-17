<?php
namespace common\models\use_traits;


trait SoftDelete {

    public static function getSoftDeleteField()
    {
        return 'delete_time';
    }

    /**
     * 自动添加时间戳，序列化参数
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //开启软删除
        $behaviors['softDeleteBehavior'] = [
            'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::className(),
            'softDeleteAttributeValues' => [
                self::getSoftDeleteField() => time(),
            ],
            'replaceRegularDelete' => true // mutate native `delete()` method
        ];
        return $behaviors;
    }

    public function foo()
    {
        return '123';
    }
    /**
     * @return \yii\db\ActiveQuery|\yii2tech\ar\softdelete\SoftDeleteQueryBehavior
     */
    public static function find($hide_delete=false)
    {
        $query = parent::find();

        $query->attachBehavior('softDelete', [
            'class' => \yii2tech\ar\softdelete\SoftDeleteQueryBehavior::className(),
            'deletedCondition' => ['not',[self::getSoftDeleteField()=>null]],
            'notDeletedCondition' => [
                self::getSoftDeleteField() => null,
            ],
        ]);

        !$hide_delete && $query = $query->notDeleted();

        return $query;
    }


    /*
     * 删除数据
     * */
    public function actionDel(array $where)
    {
        try{
            parent::actionDel($where);
            $this->beforeDelete();
            return ['code'=>1,'msg'=>'删除成功'];
        }catch (\Exception $e){
            return ['code'=>0,'msg'=>'删除异常:'.$e->getMessage()];
        }
    }
}
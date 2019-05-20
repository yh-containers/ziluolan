<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class UserAddr extends BaseModel
{
    use SoftDelete;
    const SCENARIO_OPT_DATA = 'SCENARIO_OPT_DATA';
    public static function tableName()
    {
        return '{{%user_addr}}';
    }

    public function attributeLabels()
    {
        return [
            'username'      =>  '收货人',
            'phone'         =>  '手机号码',
            'addr'          =>  '所在区域',
            'addr_extra'    =>  '详细地址',
        ];
    }
    //默认地址问题
    public function setDefault($event,$attribute)
    {
        if($this->$attribute==1){
            //调整其它关闭默认
            self::updateAll(['is_default'=>0],[
                'and',
                [
                    'uid' => $this->uid,
                    'is_default' => 1
                ],
                ['!=','id',$this->id],
            ]);
        }
        return $this->$attribute;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[]=[
            'class' => \yii\behaviors\AttributesBehavior::className(),
            'attributes' =>  [
                'is_default'  =>[
                    \yii\db\ActiveRecord::EVENT_AFTER_INSERT => [$this,'setDefault'],
                    \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => [$this,'setDefault'],
                ],
            ]
        ];
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

    public function scenarios()
    {
        $scenarios =  parent::scenarios();
        $scenarios[self::SCENARIO_OPT_DATA]=$scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
    }

    public function rules()
    {
        $rule = parent::rules();
        $rule = array_merge($rule,[
            ['is_default','default','value'=>0],
            [['uid'],'safe']
        ]);
        switch ($this->scenario){
            case self::SCENARIO_OPT_DATA: //新增或编辑
                $rule = array_merge($rule,[
                    [['username','phone','addr','addr_extra'],'required','message'=>'{attribute}不能为空'],
                    ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'请输入正确的手机号码'],
                ]);
                break;
            default:
                break;
        }
        return $rule;
    }

}
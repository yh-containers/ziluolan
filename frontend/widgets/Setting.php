<?php
namespace frontend\widgets;

use yii\base\Widget;

class Setting extends Widget
{
    public $type;
    public $field;

    public function run()
    {
        if(empty($this->type)){
            return false;
        }

        $content = \common\models\SysSetting::getContent($this->type);
        $decode_content = json_decode($content,true);
        if(empty($decode_content)){
            return $content;
        }else{
            if(empty($this->field)){
                return $decode_content;

            }else{
                return isset($decode_content[$this->field])?$decode_content[$this->field]:'';
            }
        }
    }
}
<?php
namespace frontend\widgets;

use yii\base\Widget;

class Footer extends Widget
{
    public $current_action = '';
    public function run()
    {


        return $this->render('footer',[
            'current_action' => $this->current_action,
        ]);
    }
}
<?php
namespace backend\controllers;


class SystemController extends CommonController
{


    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }

    //系统角色
    public function actionRoles()
    {
        $model = \common\models\SysRole::find()->with(['linkRoles'])->where(['pid'=>0])->orderBy('sort asc')->all();
        return $this->render('roles',[
            'model'  => $model,
        ]);
    }

    /*
    * 管理员--新增
    * */
    public function actionRolesAdd()
    {


        $id = $this->request->get('id',0);
        $model = new \common\models\SysRole();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            if(isset($php_input['node'])){
                $php_input['node'] =  array_filter($php_input['node']);
                $php_input['node'] = implode(',',$php_input['node']);
            }

            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $top_role = \common\models\SysRole::find()->asArray()->where(['pid'=>0,'status'=>1])->all();
        $model = $model::findOne($id);

        //页面所有节点
        $node = \common\models\SysNode::find()->asArray()->with('linkNode.linkNode.linkNode.linkNode')->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();

        return $this->render('rolesAdd',[
            'model' => $model,
            'top_role' => $top_role,
            'node' => $node,
        ]);
    }



    //管理员--删除
    public function actionRolesDel()
    {
        $id = $this->request->get('id');
        $model = \common\models\SysRole::findOne($id);
        if(empty($model)){
            throw new \yii\base\UserException('删除对象异常');
        }
        if($model->getAttribute('is_sys')){
            throw new \yii\base\UserException('系统指定角色无法删除');
        }
        try{
            $model->delete();
            return $this->asJson(['code'=>1,'msg'=>'删除成功']);
        }catch (\Exception $e){
            return $this->asJson(['code'=>0,'msg'=>$e->getMessage()]);
        }

    }

    /*
     * 常规设置
     * */
    public function actionSetting()
    {
        $normal_content = \common\models\SysSetting::getContent('normal');
        $normal_content = json_decode($normal_content,true);
        //固定金额
        $fixed = \common\models\SysSetting::getContent('fixed');
        $fixed = explode(',', $fixed);
        //推荐奖金设置
        $recommend = \common\models\SysSetting::getContent('recommend');
        $recommend = explode(',', $recommend);
        //推荐奖金设置
        $group_award = \common\models\SysSetting::getContent('group_award');
        $group_award = explode(',', $group_award);
        return $this->render('setting',[
            'normal_content'  => $normal_content,
            'fixed'  => $fixed,
            'recommend'  => $recommend,
            'group_award'  => $group_award,
        ]);
    }

    /*
     * 保存动作
     * */
    public function actionSettingSave()
    {
        $type = $this->request->post('type');
        $content = $this->request->post('content');
        try{
            if(is_array($content)){
                $key=key($content);
                if(is_numeric($key)){
                    $content = array_filter($content);
                    $content = implode(',',$content);

                }else{
                    $content = json_encode($content);
                }
            }
            \common\models\SysSetting::setContent($type,$content);
            return $this->asJson(['code'=>1,'msg'=>'保存成功']);
        }catch (\Exception $e) {
            return $this->asJson(['code'=>0,'msg'=>'保存异常:'.$e->getMessage()]);
        }
    }

    /*
     * 管理员列表
     * */
    public function actionManager()
    {
        $query = \common\models\SysManager::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkRole.linkParentRoles'])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('manager',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    /*
    * 管理员--新增
    * */
    public function actionManagerAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\SysManager();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            if(empty($php_input['password']))  unset($php_input['password']);

            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        $model = $model::findOne($id);
        //角色
        $roles_query = \common\models\SysRole::find()->asArray()->with(['linkRoles'=>function($query){
            return $query->where(['status'=>1]);
        }])->where(['pid'=>0,'status'=>1])->orderBy('sort asc');
        $roles = $roles_query->all();

        return $this->render('managerAdd',[
            'model' => $model,
            'roles' => $roles,
        ]);
    }



    //管理员--删除
    public function actionManagerDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\SysManager();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //协议
    public function actionProtocol()
    {
        $content = \common\models\SysSetting::getContent('protocol');
        return $this->render('protocol',[
            'content' =>$content
        ]);
    }

    //广告
    public function actionAd()
    {
        $query = \common\models\Ad::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('ad',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }
    //广告-新增、编辑
    public function actionAdAdd()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\Ad();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);

        return $this->render('adAdd',[
            'model' => $model,
        ]);
    }

    //广告--删除
    public function actionAdDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\Ad();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //导航管理
    public function actionNavAdd()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\SysNav();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        $nav = \common\models\SysNav::find()->with(['linkNav'=>function($query){
            return $query->where(['status'=>1]);
        }])->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
        return $this->render('navAdd',[
            'model' => $model,
            'nav' => $nav,
        ]);
    }


    //导航管理
    public function actionNav()
    {
        $list = \common\models\SysNav::find()->with(['linkNav.linkNav'])->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
        return $this->render('nav',[
            'list'  => $list,
        ]);
    }

    //导航管理
    public function actionNavDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\SysNav();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //导航页面管理
    public function actionNavPageAdd()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\SysNavPage();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        $nav_page = \common\models\SysNavPage::find()->with(['linkNavPage'=>function($query){
            return $query->where(['status'=>1]);
        }])->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();

        return $this->render('navPageAdd',[
            'model' => $model,
            'nav_page' => $nav_page,
        ]);
    }


    //导航页面管理
    public function actionNavPage()
    {
        $list = \common\models\SysNavPage::find()->with(['linkNavPage.linkNavPage'])->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
        return $this->render('navPage',[
            'list'  => $list,
        ]);
    }

    //导航页面管理
    public function actionNavPageDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\SysNavPage();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }
}

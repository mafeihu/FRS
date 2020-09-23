<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 16:55
 */
namespace app\admin\controller;
use think\facade\Session;
use ConstClass;
class BaseLogin extends BaseProject {
    public $login_user_info = array();
    public $admin_id = NULL;
    public function _initialize(){
        header("Content-type: text/html; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
        $login_user_info = Session::get(ConstClass::LOGIN_USER_INFO,ConstClass::SESSION_ADMIN);
        if (empty($login_user_info) || is_null($login_user_info))
        {
            $this->redirect('login/index');
        }
        else
        {
            $this->login_user_info = $login_user_info;
            $this->admin_id = $login_user_info['admin_id'];
            $this->assign('login_user_info',$login_user_info);
            $request = $this->request->param();
            $this->assign('controller',$request->controller());
        }
    }

    //切换退出账号(退出登录)
    public function logOut()
    {
        Session::clear();
        return redirect('login/index');
    }
}
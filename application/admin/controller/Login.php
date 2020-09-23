<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/23
 * Time: 13:25
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Session;
use FunctionClass;
use ConstClass;
class Login extends BaseProject
{
    public function _initialize()
    {
        header("Content-type: text/html; charset=utf-8");
        header("Access-Control-Allow-Origin: *");
    }

    //登录页面
    public function index()
    {
        Session::clear();
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    /**
     *点击登录
     */
    public function login()
    {
        //用户名
        $user_name = $this->request->param('user_name');
        //密码
        $password = $this->request->param('password');

        //非空check
        if(FunctionClass::isNullOrEmpty($user_name) || FunctionClass::isNullOrEmpty($password))
        {
            $this->assign('user_name', $user_name);
            $this->assign("errorMessage", '账号或密码不能为空');
            $this->view->engine->layout(false);
            return $this->fetch('login/index');
        }

        //用户名密码check
        $cond  = [];
        $cond['user_name'] = $user_name;
        $cond['password'] = md5($password);
        $adminInfo = Db::table('frs_admin')->where($cond)->find();
        if(FunctionClass::isNullOrEmpty($adminInfo))
        {
            $this->assign('user_name', $user_name);
            $this->assign("errorMessage", '账号或密码不正确');
            $this->view->engine->layout(false);
            return $this->fetch('login/index');
        }

        //保存userinfo
        session::set(ConstClass::LOGIN_USER_INFO,$adminInfo,ConstClass::SESSION_ADMIN);
        return redirect('blank/index');
    }
}

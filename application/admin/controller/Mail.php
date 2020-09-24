<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/24
 * Time: 10:59
 */
namespace app\admin\controller;
use think\Db;
use FunctionClass;
class Mail extends BaseLogin
{
    //初始化页面
    public function index()
    {
        $cond = [];
        $cond['id'] = 1;
        $info = DB::table('frs_mail')->where($cond)->find();
        $this->assign('info',$info);
        return $this->fetch();
    }

    /**
     * 数据保存
     */
    public function save()
    {
        $from_mail = $this->request->param('from_mail');
        $password = $this->request->param('password');
        $subject = $this->request->param('subject');
        $from_name = $this->request->param('from_name');
        $user_email = $this->request->param('user_email');
        //发件人邮箱
        if(FunctionClass::isNullOrEmpty($from_mail)
            || FunctionClass::isNullOrEmpty($password)
            || FunctionClass::isNullOrEmpty($subject)
            || FunctionClass::isNullOrEmpty($from_name)
            || FunctionClass::isNullOrEmpty($user_email)
        )
        {
            return showError('上面信息不能为空，你完善以上信息');
        }

        //数据
        $data = [];
        $data['from_mail'] = $from_mail;
        $data['password'] = $password;
        $data['subject'] = $subject;
        $data['from_name'] = $from_name;
        $data['user_email'] = $user_email;
        $data['obj_modifydate'] = date('Y-m-d H:i:s');
        //条件
        $cond = [];
        $cond['id'] = 1;
        $result = DB::table('frs_mail')->where($cond)->update($data);
        if($result)
        {
            return showSuccess('保存成功');
        }
        else
        {
            return showError('保存失败');
        }
    }
}
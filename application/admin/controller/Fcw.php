<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 16:55
 * 预警控制器管理
 */
namespace app\admin\controller;
use think\Db;
use FunctionClass;
class Fcw extends BaseLogin
{
    //初始化页面
    public function index()
    {
        $cond = [];
        $cond['id'] = 1;
        $info = DB::table('frs_fcw')->where($cond)->find();
        $this->assign('info',$info);
        return $this->fetch();
    }

    //保存
    public function save()
    {
        $minute = $this->request->param('minute');
        if(FunctionClass::isNullOrEmpty($minute))
        {
            return showError('请设置分钟数');
        }
        //数据
        $data = [];
        $data['minute'] = $minute;
        $data['obj_modifydate'] = date('Y-m-d H:i:s');
        //条件
        $cond = [];
        $cond['id'] = 1;
        $result = DB::table('frs_fcw')->where($cond)->update($data);
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

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
        $minute = Db::table('frs_fcw')->where($cond)->value('minute');
        $this->assign('minute',$minute);
        return $this->fetch();
    }

    //保存
    public function save()
    {
        $minute = $this->request->param('minute');
        if(FunctionClass::isNullOrEmpty($minute))
        {
            return false;
        }
        //数据
        $data = [];
        $data['minute'] = $minute;
        //条件
        $cond = [];
        $cond['id'] = 1;
        $result = DB::table('frs_fcw')->where($cond)->update($data);
        return redirect('fcw/index');
    }
}

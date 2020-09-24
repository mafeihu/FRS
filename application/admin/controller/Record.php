<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 16:55
 * 记录查询控制器管理
 */
namespace app\admin\controller;
use think\Db;
use FunctionClass;
class Record extends BaseLogin
{
    public function index()
    {
        //条件
        $cond = [];
        $cond['obj_status'] = 1;
        //列表
        $list = DB::table('frs_record')->where($cond)->paginate(10);
        $page = $list->render();
        $count = $list->total();
        $this->assign('count',$count);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }
}
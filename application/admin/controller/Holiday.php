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
class Holiday extends BaseLogin
{
    public function index()
    {
        //条件
        $cond = [];
        $cond['obj_status']= 1;

        //年
        $year = $this->request->param('year');
        FunctionClass::isNullOrEmpty($year) ? $cond['year'] = date('Y') : $cond['year'] = $year;

        //月
        $month = $this->request->param('month');
        FunctionClass::isNullOrEmpty($month) ? $cond['month'] = date('m') : $cond['month'] = $month;

        //列表

        $list = DB::table('frs_holiday')
            ->where($cond)
            ->order(['date'=>'desc'])
            ->limit(30)
            ->select();
        $this->assign('list', $list);
        $this->assign('date',date('Y-m'));
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function delete()
    {

        // 获取id
        $id = $this->request->param('id');
        //id空判断
        if(FunctionClass::isNullOrEmpty($id))
        {
            return showError('请求网络错误，请重新刷新网页...');
        }

        //条件
        $cond = [];
        $cond['id'] = $id;

        //数据
        $data = [];
        $data['obj_status'] = 0;
        $flg = DB::table('frs_holiday')->where($cond)->update($data);
        if($flg)
        {
            return showSuccess('删除成功');
        }
        else
        {
            return showError('请求网络错误，请重新刷新网页...');
        }
    }


    /**
     * 数据保存
     */
    public function save()
    {
        $date = $this->request->param('date');
        $name = $this->request->param('name');
        //发件人邮箱
        if(FunctionClass::isNullOrEmpty($date)
            || FunctionClass::isNullOrEmpty($name)
            || FunctionClass::isNullOrEmpty($date)
        )
        {
            return showError('上面信息不能为空，你完善以上信息');
        }

        //日期分割
        $dataArr = $var=explode("-",$date);

        //数据
        $data = [];
        $data['date'] = $date;
        $data['name'] = $name;
        $data['year'] = $dataArr[0];
        $data['month'] = $dataArr[1];
        $data['obj_modifydate'] = date('Y-m-d H:i:s');
        //条件
        $result = DB::table('frs_holiday')->insertGetId($data);
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
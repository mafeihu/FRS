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
use think\Facade\Session;

class Record extends BaseLogin
{
    /**
     *超时人员一览
     */
    public function index()
    {
        //条件
        $cond = [];
        $cond[]= ['obj_status','=',1];
        $name_like = $this->request->param('name_like');
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        $page = $this->request->param('page');
        if(FunctionClass::isNullOrEmpty($page))
        {
            //模糊搜索
            if(!FunctionClass::isNullOrEmpty($name_like))
            {
                $cond[] = ['name','like','%'.$name_like.'%'];
            }

            //开始时间
            if(!FunctionClass::isNullOrEmpty($start_date) && FunctionClass::isNullOrEmpty($end_date))
            {
                $cond[] = ['date','>=',$start_date];
            }

            //结束时间
            if(FunctionClass::isNullOrEmpty($start_date) && !FunctionClass::isNullOrEmpty($end_date))
            {
                $cond[] = ['date','<=',$end_date];
            }

            //开始结束时间都有
            if(!FunctionClass::isNullOrEmpty($start_date) && !FunctionClass::isNullOrEmpty($end_date))
            {
                $cond[] = ['date','between',[$start_date,$end_date]];
            }
            $paramData = $this->request->param();
            session::set('params',$paramData,$this->request->controller());
            Session::set('search_cond',$cond,$this->request->controller());
        }
        else
        {

            $paramData = session::get('params',$this->request->controller());
            $cond = Session::get('search_cond',$this->request->controller());
        }
        //列表
        $list = DB::table('frs_record')->where($cond)->paginate(10);
        $page = $list->render();
        $count = $list->total();
        $this->assign($paramData);
        $this->assign('count',$count);
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch();
    }
}
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
//        $month = $this->request->param('month');
//        FunctionClass::isNullOrEmpty($month) ? $cond['month'] = date('m') : $cond['month'] = $month;
        //列表
        $list = DB::table('frs_holiday')
            ->where($cond)
            ->order(['date'=>'desc'])
            ->limit(100)
            ->select();
        $this->assign('list', $list);
        $this->assign('year',date('Y'));
        return $this->fetch();
    }

    //生成节假日
    public function createHoliday()
    {
        $url= 'http://timor.tech/api/holiday/year/2020/';
        //头部定制
        $headerArray = array('Content-Type:'.'application/x-www-form-urlencoded; charset=UTF-8',);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output,true);
        if($result['code']==0 && count($result['holiday'])>0)
        {
            DB::table('frs_holiday')->where(['type'=>1])->delete(true);
            $holiday = $result['holiday'];
            foreach ($holiday as $item)
            {
                if($item['holiday'])
                {
                    $date = $item['date'];
                    //日期分割
                    $dataArr = $var=explode("-",$date);
                    //数据
                    $data = [];
                    $data['date'] = $date;
                    $data['name'] = $item['name'];
                    $data['year'] = $dataArr[0];
                    $data['month'] = $dataArr[1];
                    $data['type'] = 1;
                    $data['obj_modifydate'] = date('Y-m-d H:i:s');
                    //条件
                    $result = DB::table('frs_holiday')->insertGetId($data);
                }
            }
            return showSuccess('生成完成');
        }
    }

    //生成节假日
    public function createWeekday()
    {
        $start_date = $this->request->param('start_date');
        $end_date = $this->request->param('end_date');
        if(FunctionClass::isNullOrEmpty($start_date) || FunctionClass::isNullOrEmpty($end_date))
        {
            return showError('开始时间和结束时间不能为空');
        }

        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);
        $weekend = [];
        $satur = strtotime('this Saturday', $start_time); // 第一个星期六
        $sun = strtotime('this Sunday', $start_time); // 第一个星期日

        do {
            if ($end_time < $satur) {
                //这里防止区间只存在周日的情况
                if($end_time > $sun){
                    array_push($weekend, date('Y-m-d', $sun));
                }
                break;
            }
            array_push($weekend, date('Y-m-d', $satur));
            array_push($weekend, date('Y-m-d', $sun));

            $satur = strtotime('next Saturday', $satur); //下个星期六
            $sun = strtotime('next Sunday', $sun); //下个星期日
        } while (true);

        //数组最后一个时间大于结束时间则去除
        if ($weekend != [] && strtotime(end($weekend)) > $end_time) {
            array_pop($weekend);
        }


        //数据保存
        $weekendData = [];
        $weekarray=["星期日","星期一","星期二","星期三","星期四","星期五","星期六"];
        foreach ($weekend as $date)
        {
            $data = [];
            $data['date'] = $date;
            $data['name'] = $weekarray[date("w",strtotime($date))];
            array_push($weekendData,$data);
        }

        //数据保存
        DB::table('frs_holiday')->where(['type'=>2])->delete(true);
        foreach ($weekendData as $dayInfo)
        {
            $cond = [];
            $cond['obj_status'] = 1;
            $cond['date'] = $dayInfo['date'];
            $result = DB::table('frs_holiday')->where($cond)->find();
            if(!$result)
            {
                $date = $dayInfo['date'];
                $dataArr = $var=explode("-",$date);
                //数据
                $data = [];
                $data['date'] = $date;
                $data['name'] = $dayInfo['name'];
                $data['year'] = $dataArr[0];
                $data['month'] = $dataArr[1];
                $data['type'] = 1;
                $data['obj_modifydate'] = date('Y-m-d H:i:s');
                //条件
                $result = DB::table('frs_holiday')->insertGetId($data);
            }
        }
        return showSuccess('生成完成');
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
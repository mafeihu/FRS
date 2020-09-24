<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/24
 * Time: 16:54
 * 接口文件
 */
namespace app\api\controller;
use think\Db;

class FrsApi extends HttpCurl
{
    /**
     *预警人员处理
     */
    public function frsUserdeal()
    {
        //日志文件保存路径
        $log_file = date('YmdH').'-FRSlog.txt';
        //保存数据
        $saveData= [];
        //日期
        $date = date('Y-m-d');
        //起始日期
        $start_time = $this->getStartTime();
        //结束日期
        $end_time = time();

        //请求参数
        $requestParams = [];
        $requestParams['type'] = 1;
        $requestParams['page'] = 1;
        $requestParams['size'] = config('pageSize');
        $requestParams['start'] = $start_time;
        $requestParams['end'] = $end_time;

        //获取数据
        $result = $this->getrecordlist($requestParams);
        if($result['code']==0 && count($result['data'])>0)
        {
            $pageinfo = $result['page'];

            //总条数
            $count = $pageinfo['count'];
            //当前页码
            $page_num = $pageinfo['current'];
            //总条数
            $total_page = $pageinfo['total'];
            //每页显示条数
            $page_size = $pageinfo['size'];
            for ($page=1; $page<=$total_page; $page++)
            {
                $requestParams = [];
                $requestParams['type'] = 1;
                $requestParams['page'] = $page;
                $requestParams['size'] = config('pageSize');
                $requestParams['start'] = $start_time;
                $requestParams['end'] = $end_time;
                $result = $this->getrecordlist($requestParams);

                //考勤数据
                $record_list = $result['data'];
                //获取相机位置
                foreach ($record_list as $info)
                {
                    //照相机信息
                    $camera_position = $info['screen']['camera_position'];
                    //人员信息
                    $subject = $info['subject'];

                    //如果是出口相机直接保存
                    if($camera_position == '出相机1' || $camera_position == '出相机2')
                    {
                        //分页数据
                        $pageData = [];
                        $pageData['uuid'] = $info['uuid'];
                        $pageData['name'] = $subject['name'];
                        $pageData['avatar'] = $subject['avatar'];
                        $pageData['camera_position'] = $camera_position;
                        $pageData['out_datetime'] = $info['timestamp'];
                        $pageData['date'] = $date;
                        array_push($outData,$pageData);
                    }

                    //如果是出口相机直接保存
                    if($camera_position == '进相机1' || $camera_position == '进相机2')
                    {
                        //分页数据
                        $pageData = [];
                        $pageData['uuid'] = $info['uuid'];
                        $pageData['name'] = $subject['name'];
                        $pageData['avatar'] = $subject['avatar'];
                        $pageData['camera_position'] = $camera_position;
                        $pageData['in_datetime'] = $info['timestamp'];
                        $pageData['date'] = $date;
                        array_push($intoData,$pageData);
                    }
                }
            }

            //保存
            $result = DB::table('frs_record')->insertGetId($outData);

            











        }
    }










    //====================================================方法==============================================================//
    //=========get:识别记录列表（/event/events）=============//
    protected function getrecordlist($data=[])
    {
        $url= config('domain_name').'/event/events';
        $this->geturl($url,$data);
        return $this->object_array(json_decode($result));
    }

    //获取查询开始时间
    private function getStartTime()
    {
        $cond = [];
        $cond['id'] = 1;
        $start_time = DB::table('se_timer')->where($cond)->value('start_time');
        return $start_time;
    }




}
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
use FunctionClass;

class FrcApi extends HttpCurl
{
    /**
     *预警人员处理
     */
    public function frcUserdeal()
    {

        //日志文件保存路径
        $log_file = date('YmdH').'-FRSlog.txt';
        //获取上次执行时间
        $lastEexcTime = $this->getlastExecTime();
        //获取当前时间戳
        $nowTime = time();
        //获取当前日期
        $nowDate = date('Y-m-d');
        //获取上班时间
        $wordTimeData = $this->getWorkTime();
        //上午上班时间
        $am_start_time = $wordTimeData['am_start_time'];
        $am_end_time = $wordTimeData['am_end_time'];
        //下午上班时间
        $pm_start_time = $wordTimeData['pm_start_time'];
        $pm_end_time = $wordTimeData['pm_start_time'];

        //出数据
        $outData = [];
        //进数据
        $intoData = [];
        if(($nowTime>=$am_start_time && $nowTime<=$am_end_time) || ($nowTime>=$pm_start_time && $nowTime<=$pm_end_time))
        {

            //请求参数
            $requestParams = [];
            $requestParams['type'] = 1;
            $requestParams['page'] = 1;
            $requestParams['size'] = config('pageSize');
            $requestParams['start'] = $lastEexcTime;
            $requestParams['end'] = $nowTime;
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
                //识别记录
                $record_list = $result['data'];

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
                        $pageData['date'] = $nowDate;
                        array_push($outData,$pageData);
                    }

                    //如果是进口相机直接保存
                    if($camera_position == '进相机1' || $camera_position == '进相机2')
                    {
                        //分页数据
                        $pageData = [];
                        $pageData['uuid'] = $info['uuid'];
                        $pageData['name'] = $subject['name'];
                        $pageData['avatar'] = $subject['avatar'];
                        $pageData['camera_position'] = $camera_position;
                        $pageData['into_datetime'] = $info['timestamp'];
                        $pageData['date'] = $nowDate;
                        array_push($intoData,$pageData);
                    }
                }
            }

            //保存出去信息
            if(count($outData)>0)
            {
                DB::table('frs_fcw')->insertAll($outData);
            }

            $workNoSendEmailRecordList = [];
            //获取上班出去未回来未发送发送邮件的记录(上午)
            if($nowTime>=$am_start_time && $nowTime<=$am_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($am_start_time,$am_end_time);
            }

            //获取上班出去未回来未发送发送邮件的记录(下午)
            if($nowTime>=$pm_start_time && $nowTime<=$pm_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($am_start_time,$am_end_time);
            }

            //循环数据处理
            if(count($workNoSendEmailRecordList)>0)
            {
                foreach($workNoSendEmailRecordList as $outItem)
                {
                    foreach ($intoData as $intoItem)
                    {
                        if($outItem['uuid'] == $intoItem['uuid'] && is_null($intoItem['into_datetime']))
                        {
                            $cond = [];
                            $cond['id'] = $outItem['id'];

                            $data = [];
                            $data['into_datetime'] = $intoItem['into_datetime'];
                            DB::table('frs_record')->where($cond)->update($data);
                        }
                    }
                }
            }

            //获取上班出去未回来未发送发送邮件的记录(上午)
            if($nowTime>=$am_start_time && $nowTime<=$am_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($am_start_time,$am_end_time);
            }

            //获取上班出去未回来未发送发送邮件的记录(下午)
            if($nowTime>=$pm_start_time && $nowTime<=$pm_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($am_start_time,$am_end_time);
            }

            //获取预警数据
            $minute = $this->getFrcMinute();
            $frc_second = $minute*60;
            $frcData = [];
            if(count($workNoSendEmailRecordList)>0)
            {
                foreach ($workNoSendEmailRecordList as $item)
                {
                    if(!FunctionClass::isNullOrEmpty($item['out_datetime']) && !FunctionClass::isNullOrEmpty($item['into_datetime']))
                    {
                        $goOutTime = bcsub($item['into_datetime'],$item['out_datetime']);
                    }
                    elseif (!FunctionClass::isNullOrEmpty($item['out_datetime']) && FunctionClass::isNullOrEmpty($item['into_datetime']))
                    {
                        $goOutTime = bcsub($nowTime,$item['out_datetime']);
                    }
                    elseif(!FunctionClass::isNullOrEmpty($item['out_datetime']))
                    {
                        if($nowTime>=$am_start_time && $nowTime<=$am_end_time)
                        {
                            $goOutTime = bcsub($am_end_time,$item['out_datetime']);
                        }

                        //获取上班出去未回来未发送发送邮件的记录(下午)
                        if($nowTime>=$pm_start_time && $nowTime<=$pm_end_time)
                        {
                            $goOutTime = bcsub($pm_end_time,$item['out_datetime']);
                        }
                    }

                    //预警数据整合
                    if($goOutTime>$frc_second)
                    {
                        array_push($frcData,$item);
                    }
                }
            }

            //数据更新邮件发送
            if(count($frcData)>0)
            {
                //发送邮件内容
                $content = '';
                $content .= "<ul>";
                foreach ($frcData as $info)
                {
                    $content .= "<li>{$info['name']}:{$info['out_datetime']}-{$info['into_datetime']} 超时:20分钟</li>";
                }
                $content .= "</ul>";

                //邮件发送
                $cond = [];
                $cond['id'] = 1;
                $emailConfig = DB::table('frs_mail')->where($cond)->find();
                $this->sendEmail($emailConfig,$content);

                //数据更新
                foreach ($frcData as $item)
                {
                    $cond = [];
                    $cond['id'] = $item['id'];

                    $data = [];
                    $data['send_flg'] = 1;
                    DB::table('frs_record')->where($cond)->update($data);
                }
            }
        }
    }






        


















































    //====================================================方法==============================================================//
    //=========get:识别记录列表（/event/events）=============//
    protected function getrecordlist($data=[])
    {
        $url= config('domain_name').'/event/events';
        $result = $this->geturl($url,$data);
        return $this->object_array(json_decode($result));
    }

    //获取预警时间
    private function getFrcMinute()
    {
        $cond = [];
        $cond['id'] = 1;
        $minute = DB::table('frs_fcw')->where($cond)->value('minute');
        return $minute;
    }


    //获取上次执行时间
    private function getlastExecTime()
    {
        $cond = [];
        $cond['id'] = 1;
        $start_time = DB::table('frs_fcw')->where($cond)->value('exec_time');
        return strtotime($start_time);
    }

    //获取上班时间
    private function getWorkTime()
    {
        $cond = [];
        $cond['id'] = 1;
        $fcwInfo = DB::table('frs_fcw')->where($cond)->find();
        //凌晨时间
        $nowDate = date('Y-m-d');
        //上午上班时间计算
        $am_start_time = $nowDate." ".$fcwInfo['am_start_time'];
        $am_end_time = $nowDate." ".$fcwInfo['am_end_time'];

        //下午上班时间计算
        $pm_start_time = $nowDate." ".$fcwInfo['pm_start_time'];
        $pm_end_time = $nowDate." ".$fcwInfo['pm_end_time'];

        $dateData = [];
        $dateData['am_start_time'] = strtotime($am_start_time);
        $dateData['am_end_time'] = strtotime($am_end_time);
        $dateData['pm_start_time'] = strtotime($pm_start_time);
        $dateData['pm_end_time'] = strtotime($pm_end_time);
        return $dateData;
    }

    /**
     *获取未发送邮件的出去人员信息
     */
    private function getWorkNoSendEmailRecordList($start_work_time,$end_work_time)
    {
        $cond = [];
        $cond['send_flg'] = 1;
        $cond['obj_status'] = 1;
        $cond['out_datetime'] = ['between',[$start_work_time,$end_work_time]];
        $list = DB::table('frs_record')->where($cond)->select();
        return $list;
    }
}
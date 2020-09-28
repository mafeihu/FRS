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

class FrcApi extends TextData1
{
    /**
     *预警人员处理
     */
    public function frcUserdeal()
    {
        //授权登陆
        //日志文件保存路径
        $log_file = date('YmdH').'-FRSlog.txt';
        $authLogin = $this->authLogin();
        if($authLogin['code']==0 && count($authLogin['data'])>0)
        {
            $auth_token = $authLogin['data']['auth_token'];
        }else
        {

            $log_str = date('Y-m-d H:i:s').'登陆失败';
            $this->logCreate($log_file,$log_str);
            return false;
        }

        //进摄像头
        $into_camera_position = [1,3];
        //出摄像头
        $out_camera_position = [2,4];
        //获取上次执行时间
        $lastEexcTime = $this->getlastExecTime();
        //获取当前时间戳
        $nowTime = time();
        //获取当前日期
        $nowDate = date('Y-m-d');
        //获取上班时间
        $wordTimeData = $this->getWorkTime();
        //上午上班时间s时间戳
        $am_start_time = $wordTimeData['am_start_time'];
        $am_end_time = $wordTimeData['am_end_time'];
        //上午上班日期时间
        $am_start_datetime = date('Y-m-d H:i:s',$am_start_time);
        $am_end_datetime = date('Y-m-d H:i:s',$am_end_time);

        //下午上班时间时间戳
        $pm_start_time = $wordTimeData['pm_start_time'];
        $pm_end_time = $wordTimeData['pm_end_time'];
        //下午上班日期时间
        $pm_start_datetime = date('Y-m-d H:i:s',$pm_start_time);
        $pm_end_datetime = date('Y-m-d H:i:s',$pm_end_time);
        //出数据
        $outData = [];
        //进数据
        $intoData = [];

        //计算临界时间
        $minute = $this->getFrcMinute();
        $frc_second = $minute * 60;
        $thisEexcTime= $lastEexcTime + $frc_second;
        //获取上午初始时间
        if($thisEexcTime>=$am_start_time && $lastEexcTime<=$am_start_time)
        {
            $lastEexcTime = $am_start_time;
        }
        //获取下午初始时间
        if($thisEexcTime>=$pm_start_time && $lastEexcTime<=$pm_start_time)
        {
            $lastEexcTime = $pm_start_time;
        }
        //判断是否在工作时间内
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
            $result = $this->getrecordlist($auth_token,$requestParams);
            if($result['code']==0 && count($result['data'])>0)
            {
                //获取总页数
                $pageinfo = $result['page'];
                $total_page = $pageinfo['total'];
                for ($page=1; $page<=$total_page; $page++)
                {
                    //获取数据
                    $requestParams['size'] = config('pageSize');
                    $result = $this->getrecordlist($auth_token,$requestParams);
                    //识别记录数据整合
                    $record_list = $result['data'];
                    //接口数据处理
                    $apiData = $this->getApiData($into_camera_position,$out_camera_position,$result);
                    foreach ($apiData as $info)
                    {
                        //摄像头
                        $camera_position = $info['camera_position'];
                        //如果是出口相机直接保存
                        if(in_array($camera_position,$out_camera_position))
                        {
                            //分页数据
                            $pageData = [];
                            $pageData['uuid'] = $info['subject_id'];
                            $pageData['name'] = $info['name'];
                            $pageData['avatar'] = $info['avatar'];
                            $pageData['camera_position'] = $camera_position;
                            $pageData['out_datetime'] = date('Y-m-d H:i:s',$info['timestamp']);;
                            $pageData['date'] = $nowDate;
                            array_push($outData,$pageData);
                        }


                        //如果是进口相机直接保存
                        if(in_array($camera_position,$into_camera_position))
                        {
                            //分页数据
                            $pageData = [];
                            $pageData['uuid'] = $info['subject_id'];
                            $pageData['name'] = $info['name'];
                            $pageData['avatar'] = $info['avatar'];
                            $pageData['camera_position'] = $camera_position;
                            $pageData['timestamp'] = date('Y-m-d H:i:s',$info['timestamp']);
                            array_push($intoData,$pageData);
                        }
                    }

                }
            }
            //保存出去信息
            if(count($outData)>0)
            {
                //保存出去数据
                DB::table('frs_record')->insertAll($outData);
                //重新保存进入数据
                Db::name('frs_api_record')->delete(true);
                DB::table('frs_api_record')->insertAll($intoData);
            }

            $workNoSendEmailIntoNullRecordList = [];
            //获取上班出去未回来未发送发送邮件的记录(上午)
            if($nowTime>=$am_start_time && $nowTime<=$am_end_time)
            {
                $workNoSendEmailIntoNullRecordList = $this->getWorkNoSendEmailIntoNullRecordList($am_start_datetime,$am_end_datetime);
            }

            //获取上班出去未回来未发送发送邮件的记录(下午)
            if($nowTime>=$pm_start_time && $nowTime<=$pm_end_time)
            {
                $workNoSendEmailIntoNullRecordList = $this->getWorkNoSendEmailIntoNullRecordList($pm_start_datetime,$pm_end_datetime);
            }

            //pre($workNoSendEmailIntoNullRecordList);exit;

            //进入的数据
            $apiInfoRecordList = $this->getApiInfoRecordList($lastEexcTime,$nowTime);
            //数据整合
            $uuidRecordInfoData = $this->getPackageRecordData($workNoSendEmailIntoNullRecordList,$apiInfoRecordList);
            //更新进入信息
            if(count($uuidRecordInfoData)>0)
            {
                foreach ($uuidRecordInfoData as $uuidRecordInfo)
                {
                    $data = [];
                    $data['into_datetime'] = $uuidRecordInfo['into_datetime'];
                    $data['obj_modifydate'] = date('Y-m-d H:i:s');
                    DB::name('frs_record')->where(['id'=>$uuidRecordInfo['id']])->update($data);
                }
            }

            $workNoSendEmailRecordList = [];
            //获取上班出去未回来未发送发送邮件的记录(上午)
            if($nowTime>=$am_start_time && $nowTime<=$am_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($am_start_datetime,$am_end_datetime);
            }

            //获取上班出去未回来未发送发送邮件的记录(下午)
            if($nowTime>=$pm_start_time && $nowTime<=$pm_end_time)
            {
                $workNoSendEmailRecordList = $this->getWorkNoSendEmailRecordList($pm_start_datetime,$pm_end_datetime);
            }

            //获取预警人员信息
            $fcwData = $this->getComputeFcwRecordList($workNoSendEmailRecordList,$nowTime,$am_start_time,$am_end_time,$pm_start_time,$pm_end_time);
            if (count($fcwData)>0)
            {
                //获取邮件内容
                $mailContent = $this->getSendMailCount($fcwData,$nowTime);

                //邮件发送
                $cond = [];
                $cond['id'] = 1;
                $emailConfig = DB::table('frs_mail')->where($cond)->find();
                $result = $this->sendEmail($emailConfig, $mailContent);

                //更新发送发送邮件数据
                if($result)
                {
                    foreach ($fcwData as $item)
                    {
                        DB::table('frs_record')->where(['id'=>$item['id']])->update(['send_flg'=>1]);
                    }
                }
            }

            //更新执行时间
            $this->setlastExecTime($nowTime);
        }
        else
        {
            //更新执行时间
            $this->setlastExecTime($nowTime);
        }
    }

    //====================================================方法==============================================================//
    //=========get:识别记录列表（/event/events）=============//
    protected function getrecordlist($auth_token,$data=[])
    {
        $url= config('domain_name').'/event/events';
        $result = $this->geturl($url,$auth_token,$data);
        return $this->object_array(json_decode($result));

    }

    //=========post:授权登陆（/event/events）=============//
    protected function authLogin()
    {
        $requestParams = [];
        $requestParams['username'] = config('username');
        $requestParams['password'] = config('password');
        $requestParams['auth_token'] = true;
        $url= config('domain_name').'/auth/login';
        $result = $this->posturl($url,$requestParams);
        return $this->object_array(json_decode($result));
    }

    //接口数据整合
    protected function getApiData($into_camera_position,$out_camera_position,$apiData)
    {
        $resultData = [];
        foreach ($apiData as $key=>$info)
        {
            if($info['subject_id']>0)
            {
                //照相机信息
                $camera_position = $info['screen']['camera_position'];
                $avatar = $info['subject']['avatar'];

                //分页数据
                $pageData = [];
                $pageData['uuid'] = $info['subject_id'];
                $pageData['name'] = $info['name'];
                $pageData['avatar'] = $avatar;
                $pageData['camera_position'] = $camera_position;
                $pageData['timestamp'] = $info['timestamp'];
                array_push($resultData,$pageData);
            }
        }
        //获取所有人
        $uuidResultData = [];
        foreach($resultData as $info)
        {
            if(!in_array($info['uuid'],$uuidResultData))
            {
                array_push($uuidAllData,$info['uuid']);
            }
        }

        //按照人进行数据分组
        $uuidGroupData = [];
        foreach ($uuidResultData as $uuid)
        {
            $uuidGroupData[$uuid] = [];
            foreach ($resultData as $info)
            {
                if($uuid==$info['uuid'])
                {
                    $uuidGroupData[$uuid][] = $info;
                }
            }
        }

        //对连续进出的数据进行去重
        $resturnData = [];
        foreach ($uuidGroupData as $uuidInfodata)
        {

            foreach ($uuidInfodata as $key=>$uuidInfo)
            {
                //echo $key;
                //当前摄像头位置
                $this_camera_position = $uuidInfo['camera_position'];
                //如果出去的数据出现连续的时候(最近一条)
                if(in_array($this_camera_position,$out_camera_position))
                {
                    //上一条摄像头位置
                    if($key>0)
                    {
                        //echo $key;
                        $last_camera_position = $uuidInfodata[$key-1]['camera_position'];
                        echo $last_camera_position;
                        if(in_array($last_camera_position,$into_camera_position))
                        {
                            array_push($resturnData,$uuidInfo);
                        }
                    }
                    else
                    {
                        array_push($resturnData,$uuidInfo);
                    }
                }

                //如果进的数据出现连续的时候(最近一条)
                if(in_array($this_camera_position,$into_camera_position))
                {

                    //上一条摄像头位置
                    if($key<count($uuidInfodata)-1)
                    {
                        $next_camera_position = $uuidInfodata[$key+1]['camera_position'];
                        if(in_array($next_camera_position,$out_camera_position))
                        {
                            array_push($resturnData,$uuidInfo);
                        }

                    }
                    else
                    {
                        array_push($resturnData,$uuidInfo);
                    }
                }
            }
        }
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

    //设置上次执行时间
    private function setlastExecTime($exec_tiem)
    {
        //var_dump($exec_tiem);exit;
        //条件
        $cond = [];
        $cond['id'] = 1;

        //数据
        $data = [];
        $data['exec_time'] = date('Y-m-d H:i:s',$exec_tiem);
        $result = DB::table('frs_fcw')->where($cond)->update($data);
        return $result;
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
        $dateData['now_time'] = time();
        $dateData['am_start_time'] = strtotime($am_start_time);
        $dateData['am_end_time'] = strtotime($am_end_time);
        $dateData['pm_start_time'] = strtotime($pm_start_time);
        $dateData['pm_end_time'] = strtotime($pm_end_time);
        return $dateData;
    }

    //获取每次定时任务接口保存的进入信息
    private function getApiInfoRecordList($start_time,$end_time)
    {
        //排序
        $order = [];
        $order['timestamp'] = 'asc';

        //查询
        $list = DB::table('frs_api_record')
            ->where('obj_status','=',1)
            //->where('timestamp','between',[$start_time,$end_time])
            ->order($order)
            ->select();
        return $list;
    }

    /**
     *获取未发送邮件的出去人员信息
     */
    private function getWorkNoSendEmailRecordList($start_work_time,$end_work_time)
    {
        //排序
        $order = [];
        $order['out_datetime'] = 'asc';

        //查询
        $list = DB::table('frs_record')
            ->where('send_flg','=',0)
            ->where('obj_status','=',1)
            ->where('out_datetime','between',[$start_work_time,$end_work_time])
            ->order($order)
            ->select();
        return $list;
    }

    private function getWorkNoSendEmailIntoNullRecordList($start_work_time,$end_work_time)
    {
        //排序
        $order = [];
        $order['out_datetime'] = 'asc';
        //查询
        $list = DB::table('frs_record')
            ->where('send_flg','=',0)
            ->where('into_datetime','null')
            ->where('obj_status','=',1)
            ->where('out_datetime','between',[$start_work_time,$end_work_time])
            ->order($order)
            ->select();
        return $list;
    }

    /**
     * 获取预警人员信息
     */
    private function getComputeFcwRecordList($workNoSendEmailRecordList,$nowTime,$am_start_time,$am_end_time,$pm_start_time,$pm_end_time)
    {
        //获取预警数据
        $minute = $this->getFrcMinute();
        $frc_second = $minute * 60;
        $frcData = [];
        foreach ($workNoSendEmailRecordList as $item) {
            //转化成时间戳
            $into_time = strtotime($item['into_datetime']);
            $out_time  = strtotime($item['out_datetime']);
            if (!FunctionClass::isNullOrEmpty($item['out_datetime']) && !FunctionClass::isNullOrEmpty($item['into_datetime'])) {
                $goOutTime = bcsub($into_time, $out_time, 0);
            } elseif (!FunctionClass::isNullOrEmpty($item['out_datetime']) && FunctionClass::isNullOrEmpty($item['into_datetime'])) {
                $goOutTime = bcsub($nowTime, $out_time, 0);
            } elseif (!FunctionClass::isNullOrEmpty($item['out_datetime'])) {

                //获取上班出去未回来未发送发送邮件的记录(上午)
                if ($nowTime >= $am_start_time && $nowTime <= $am_end_time) {
                    $goOutTime = bcsub($am_end_time, $out_time, 0);
                }

                //获取上班出去未回来未发送发送邮件的记录(下午)
                if ($nowTime >= $pm_start_time && $nowTime <= $pm_end_time) {
                    $goOutTime = bcsub($pm_end_time, $out_time, 0);
                }
            }

            //预警数据整合
            if ($goOutTime > $frc_second) {
                array_push($frcData, $item);
            }
        }
        //数据返回
        return $frcData;
    }

    /**
     * 进出记录数据整合
     */
    public function getPackageRecordData($outdata,$infoData)
    {
        //获取UUID列表
        $uuidList = [];
        foreach ($outdata as $outItem)
        {
            if(!in_array($outItem['uuid'],$uuidList))
            {
                array_push($uuidList,$outItem['uuid']);
            }
        }

        //出去数据整合
        $uuidOutData = [];
        foreach ($uuidList as $key=>$uuid)
        {
            $uuidOutData[$uuid] = [];
            foreach ($outdata as $outInfo)
            {
                if($outInfo['uuid']==$uuid)
                {
                    array_push($uuidOutData[$uuid],$outInfo);
                }
            }
        }

        //获取出去的第一条数据
        $uuidOutFirstData = [];
        foreach ($uuidOutData as $uuid=>$uuidOutFirstInfo)
        {
            $uuidOutFirstData[$uuid] = $uuidOutFirstInfo[0];
        }


        //进入数据整合
        $uuidIntoData = [];
        foreach ($uuidList as $key=>$uuid)
        {
            $uuidIntoData[$uuid] = [];
            foreach ($infoData as $outInfo)
            {
                if($outInfo['uuid']==$uuid)
                {
                    //排除迟到的
                    if($outInfo['timestamp']>$uuidOutFirstData[$uuid]['out_datetime'])
                    {
                        array_push($uuidIntoData[$uuid],$outInfo);
                    }

                }
            }
        }

        //进出数据整合
        $uuidRecordInfoData = [];
        foreach ($uuidList as $uuid)
        {
            $uuidOutInfo = $uuidOutData[$uuid];
            $uuidIntoInfo = $uuidIntoData[$uuid];
            //获取循环用户的出记录条数
            $uuidCount = count($uuidOutInfo);
            for ($key=0; $key<$uuidCount; $key++)
            {
                //只有进入的时间
                if(isset($uuidIntoInfo[$key]['timestamp']))
                {
                    $uuidRecordInfo = [];
                    $uuidRecordInfo['id'] = $uuidOutInfo[$key]['id'];
                    $uuidRecordInfo['uuid'] = $uuidOutInfo[$key]['uuid'];
                    $uuidRecordInfo['out_datetime'] = $uuidOutInfo[$key]['out_datetime'];
                    $uuidRecordInfo['into_datetime'] = $uuidIntoInfo[$key]['timestamp'];
                    array_push($uuidRecordInfoData,$uuidRecordInfo);
                }

            }
        }
        return $uuidRecordInfoData;
    }

    /**
     * 获取发送邮件内容
     */
    private function getSendMailCount($frcData,$nowTime)
    {
        //pre($frcData);
        //发送邮件内容
        $content = '';
        $content .= "<ul>";
        foreach ($frcData as $info)
        {
            $into_datetime= FunctionClass::isNullOrEmpty($info['into_datetime']) ? date("Y-m-d H:i:s",$nowTime) : $info['into_datetime'];
            $content .= "<li>{$info['name']}出截止时间:{$info['out_datetime']}至{$into_datetime}已超出预警时间</li>";
        }
        $content .= "</ul>";
        return $content;
    }
}
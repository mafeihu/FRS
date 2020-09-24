<?php
namespace app\controller;

use app\common\BLL\UserBLL;
use think\Db;
class Api extends HttpCurl
{
//================================================接口请求方法======================================================//
    //用添加
    public function userInsert()
    {
        $data = [];
        $data['user_name'] = '测试';
        $id = UserBLL::userInsert($data);
        return $id;
    }


    /**
     *获取打卡记录并且入库
     */
    public function getRecordDataInsert()
    {
        //日志文件保存路径
        $log_file = date('YmdH').'-recordDataInsert.txt';
        //保存数据
        $saveData= [];
        //起始日期
        $date_time_from = $this->getDateTimeFrom();
        //结束日期
        $date_time_to = date('Y-m-d H:i:s');
        //请求参数
        $requestParams = [];
        $requestParams['type'] = 1;
        $requestParams['page'] = 1;
        $requestParams['size'] = config('pageSize');
        $requestParams['date_time_from'] = $date_time_from;
        $requestParams['date_time_to'] = $date_time_to;
        $result = $this->getrecordlist($requestParams);
        if($result['code']==200 && $result['message']=='OK')
        {
            //数据
            $data = $result['data'];
            //总条数
            $total = $data['total'];
            //当前页码
            $page_num = $data['page_num'];
            //每页显示条数
            $page_size = $data['page_size'];
            //总页数
            $total_page = $data['total_page'];
            for ($page=1; $page<=$total_page; $page++)
            {
                echo $page;
                $requestParams = [];
                $requestParams['type'] = 1;
                $requestParams['page'] = $page;
                $requestParams['size'] = config('pageSize');
                $requestParams['date_time_from'] = $date_time_from;
                $requestParams['date_time_to'] = $date_time_to;
                $result = $this->getrecordlist($requestParams);

                //考勤数据
                $data = $result['data']['data'];
                $record_list = $data['record_list'];
                foreach ($record_list as $item)
                {
                    if(empty($item['job_number']) || is_null($item['job_number']))
                    {
                        //插入完成记录数据
                        $log_str = date('Y-m-d H:i:s').'记录ID:'.$item['id'].'没有工号';
                        $this->logCreate($log_file,$log_str);
                    }
                    else
                    {
                        //分页数据
                        $pageData = [];
                        $pageData['record_id'] = $this->checkFiled($item['id']);
                        $pageData['user_id'] = $item['user_id'];
                        $pageData['user_name'] = $this->checkFiled($item['user_name']);
                        $pageData['job_number'] = $this->checkFiled($item['job_number']);
                        $pageData['user_ic_number'] = $this->checkFiled($item['user_ic_number']);
                        $pageData['user_id_number'] = $this->checkFiled($item['user_id_number']);
                        $pageData['group_id'] = $item['group_id'];
                        $pageData['group_name'] = $this->checkFiled($item['group_name']);
                        $pageData['sign_time'] = date('Y-m-d H:i:s',$item['sign_time']);;
                        $pageData['sign_date'] = $this->checkFiled($item['sign_date']);
                        $pageData['location'] = $this->checkFiled($item['location']);
                        $pageData['device_ldid'] = $this->checkFiled($item['device_ldid']);
                        array_push($saveData,$pageData);
                    }

                }
            }
        }
        else
        {
            //插入完成记录数据
            $log_str = date('Y-m-d H:i:s').'暂无数据获取';
            $this->logCreate($log_file,$log_str);
        }
        //数据入库
        $count = 0;
        if(!empty($saveData) && count($saveData)>0)
        {
            foreach ($saveData as $item)
            {
                $result = DB::table('se_user_record')->insert($item);
                if($result)
                {
                    $count++;
                }
                else
                {
                    //失败写入日志
                    $log_str = json_encode($item).'<br/>';
                    $this->logCreate($log_file,$log_str);
                }
            }
            //插入完成记录数据
            $log_str = date('Y-m-d H:i:s').'共添加'.$count.'条数据';
            $this->logCreate($log_file,$log_str);

            //更新时间
            //$this->setDateTimeFrom($date_time_to);
        }
        //更新时间
        $this->setDateTimeFrom($date_time_to);
        $log_str = date('Y-m-d H:i:s').'暂无数据获取';
        $this->logCreate($log_file,$log_str);
    }
    //===============================get:查询识别记录（/api/v3/record/list）===========================//
    protected function getrecordlist($data=[])
    {
        $api_url = '/api/v3/record/list';
        $path = $this->getAuthenticationUrl($api_url);
        $result = $this->get($path,$data);
        return $this->object_array(json_decode($result));
    }
    //===============================get:查询识别记录（/api/v3/record/list）===========================//
    protected function getVersion($data=[])
    {
        $api_url = '/api/v1/server/version';
        $path = $this->getAuthenticationUrl($api_url);
        $result = $this->get($path,$data);
        return $this->object_array(json_decode($result));
    }
    //=================================================方法================================================//

    //获取查询开始时间
    private function getDateTimeFrom()
    {
        $cond = [];
        $cond['id'] = 1;
        $date_time_from = DB::table('se_timer')->where($cond)->value('date_time_from');
        return $date_time_from;
    }
    //设置查询开始时间
    private function setDateTimeFrom($date_time_from)
    {
        //条件
        $cond = [];
        $cond['id'] = 1;

        //数据
        $data = [];
        $data['date_time_from'] = $date_time_from;
        $date_time_from = DB::table('se_timer')->where($cond)->update($data);
        return $date_time_from;
    }

    //鉴权数据获取
    private function getAuthenticationUrl($api_url)
    {
        $app_secret = config('app_secret');
        $app_key = config('app_key');
        $timestamp = $this->getCurrentMilis();
        $sign_str = $timestamp.'#'.$app_secret;
        $sign = md5($sign_str);

        //数据整合
        $data = [];
        $data['timestamp'] = $timestamp;
        $data['sign'] = $sign;
        $data['app_key'] = $app_key;
        $domain_name = config('domain_name');
        $path = $domain_name.$api_url.'?'.$this->buildGetQuery($data);
        return $path;
    }

    //日志文件
    private function logCreate($file,$log_str)
    {
        //创建目录
        $directory = 'log/'.date('Ymd');
        if(!is_dir($directory))
        {
            $result = mkdir(iconv("UTF-8", "GBK", $directory),0777,true);
            if($result)
            {
                $file_path = $directory.'/'.$file;
                file_put_contents($file_path, date("Y-m-d H:i:s").' w :'.$log_str.' ; '.PHP_EOL, FILE_APPEND);
            }
        }
        else
        {
            $file_path = $directory.'/'.$file;
            file_put_contents($file_path, date("Y-m-d H:i:s").' w :'.$log_str.' ; '.PHP_EOL, FILE_APPEND);
        }
    }

    //毫秒时间戳
    private function getCurrentMilis(){
        $mill_time = microtime();
        $timeInfo = explode(' ', $mill_time);
        $milis_time = sprintf('%d%03d',$timeInfo[1],$timeInfo[0] * 1000);
        return $milis_time;
    }
    
    /**
     * 数据转化
     */
    private function checkFiled($filed)
    {
        if(!isset($filed) || is_null($filed) || empty($filed))
        {
            return $filed = '';
        }
        return $filed;
    }
}


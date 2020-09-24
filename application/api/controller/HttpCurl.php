<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14
 * Time: 9:26
 * CURL设置请求类
 */
namespace app\api\controller;
use think\Controller;
Class HttpCurl extends BaseApi
{
    public $host;
    public $port;
    public $path;
    public $method;
    public $postdata = '';
    public $cookiefile = "";//服务器绝对路径
    public $referer;
    public $accept = 'text/xml,application/xml,application/xhtml+xml,text/html,text/plain,image/png,image/jpeg,image/gif,*/*';
    public $content_type = 'application/json';
    public $user_agent = "Koala Admin";
    public $authorization = "e3f14076-571e-4243-b315-bbc4d34547d4";
    public $timeout = 20;
    public $use_gzip = true;
    public $persist_cookies = true;

    //初始化方法
    protected function initialize()
    {
        header("Content-type: text/html; charset=utf-8");
        $this->host = config('domain_name');
        $this->setUserAgent('Koala Admin');
    }

    //GET请求方式
    function geturl($path, $data=[]){
        //路由拼接
        $this->path = $path;
        if (!empty($data) && count($data)>0)
        {
            $this->path .= '?'.$this->buildGetQuery($data);
        }

        //头部定制
        $headerArray = array(
            "Content-type: text/html; charset=utf-8",
            "Content-Type: application/json",
            'Authorization: '.$this->authorization
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //POST请求方式
    function posturl($url,$data){
        //数据转化
        $this->postdata = $this->buildJsonQuery($data);
        //头部定制
        $headerArray =array(
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json",
            'Authorization: '.$this->authorization
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->postdata);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //PUT请求方式
    function puturl($path,$data){
        $this->path = $path;
        $this->postdata = $this->buildJsonQuery($data);

        //头部定制
        $headerArray =array(
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json",
            'Authorization: '.$this->authorization
        );
        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $path); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postdata);//设置提交的字符串
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //DELETE请求方式
    function delurl($path,$data=[]){

        //数据转化
        $this->path = $path;
        $this->postdata = $this->buildJsonQuery($data);

        //头部定制
        $headerArray =array(
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json",
            'Authorization: '.$this->authorization
        );
        $ch = curl_init();
        curl_setopt ($ch,CURLOPT_URL,$this->path);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->postdata);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //PATCH请求方式
    function patchurl($path,$data){
        //数据转化
        $this->path = $path;
        $this->postdata = $this->buildJsonQuery($data);

        //头部定制
        $headerArray =array(
            "Content-type:application/json;charset='utf-8'",
            "Accept:application/json",
            'Authorization: '.$this->authorization
        );
        $ch = curl_init();
        curl_setopt ($ch,CURLOPT_URL,$this->path);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->postdata);     //20170611修改接口，用/id的方式传递，直接写在url中了
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    //=================================================方法================================================//

    //球球数据转化方法
    public function buildGetQuery($data)
    {
        $querystring = '';
        if (is_array($data)) {

            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $val2) {
                        $querystring .= urlencode($key).'='.urlencode($val2).'&';
                    }
                } else {
                    $querystring .= urlencode($key).'='.urlencode($val).'&';
                }
            }
            $querystring = substr($querystring, 0, -1); // Eliminate unnecessary &
        } else {
            $querystring = $data;
        }
        return $querystring;
    }


    //Json数据格式化方法
    function buildJsonQuery($data)
    {
        $querystring = '';
        if (is_array($data)) {
            $querystring=json_encode($data);
        }
        else echo "Parameters should be array.";
        return $querystring;
    }

    //对象转化成数组方法
    function object_array($array)
    {
        if(is_object($array))
        {
            $array = (array)$array;
        }
        if(is_array($array))
        {
            foreach($array as $key=>$value)
            {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    //设置UserAgent
    private function setUserAgent($string)
    {
        $this->user_agent = $string;
    }

    //设置cookie
    private function setcookiefile($filepath)
    {
        $this->cookiefile = realpath($filepath);
    }

    //获取cookie文件路径
    private function getcookie()
    {
        if(file_exists($this->cookiefile))
        {
            $content = file_get_contents($this->cookiefile);
            return $content;
        }
        else return null;
    }
}
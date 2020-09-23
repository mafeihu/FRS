<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//常量
class ConstClass{
    const MSG_KEY = 'Msg';
    const MSG_001 = '网络链接错误，刷新页面重试。';
    const MSG_002 = '添加成功。';
    const MSG_003 = '添加失败。';
    const MSG_004 = '获取成功。';
    //分页条数
    const PAGE_COUNT1000 = 1000;
    const PAGE_COUNT500 = 500;
    const PAGE_COUNT10 = 10;
    const PAGE_COUNT20 = 20;
    const PAGE_COUNT30 = 30;


    //登录用户常量
    const  SESSION_ADMIN = 'SESSION_ADMIN';//管理员登录session
    const  LOGIN_USER_INFO = 'LOGIN_USER_INFO';
}


//方法
class FunctionClass{
    static public function isNullOrEmpty($str)
    {
        return is_null($str) || $str === '';
    }
}



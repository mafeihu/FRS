<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/24
 * Time: 16:54
 * 接口文件
 */
namespace app\api\controller;
use think\Controller;
use think\Db;
use FunctionClass;
class TextData1 extends HttpCurl
{
    public function getCeshiData()
    {
        $record_list = [
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-27 09:05:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-27 09:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-27 09:35:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],

            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-27 09:05:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-27 09:08:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-27 09:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],

    ];
        return $record_list;
    }

}









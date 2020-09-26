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
class TextData extends HttpCurl
{
    public function getCeshiData()
    {
        $record_list = [
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 15:35:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 15:20:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 16:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 14:15:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 14:45:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 16:05:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 16:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 16:20:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 16:35:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],


            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 15:10:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 15:15:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>1,
                'timestamp'=>strtotime('2020-09-26 15:40:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张一','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 14:10:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 14:25:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>2,
                'timestamp'=>strtotime('2020-09-26 14:55:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张二','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 16:10:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 16:15:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],
            [
                'uuid'=>3,
                'timestamp'=>strtotime('2020-09-26 40:10:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'subject'=>['name'=>'张三','avatar'=>'header.png']
            ],


        ];
        return $record_list;
    }

}









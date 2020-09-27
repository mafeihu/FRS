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
                'screen_id'=>1,
                'timestamp'=>strtotime('2020-09-27 16:05:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'name' => '张一',
                'avatar'=>'header.png'
            ],
            [
                'screen_id'=>1,
                'timestamp'=>strtotime('2020-09-27 16:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'name' => '张一',
                'avatar'=>'header.png'
            ],
            [
                'screen_id'=>1,
                'timestamp'=>strtotime('2020-09-27 16:50:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'name' => '张一',
                'avatar'=>'header.png'
            ],

            [
                'screen_id'=>2,
                'timestamp'=>strtotime('2020-09-27 17:10:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'name' => '张二',
                'avatar'=>'header.png'
            ],
            [
                'screen_id'=>2,
                'timestamp'=>strtotime('2020-09-27 17:20:00'),
                'screen'=>['camera_position'=>'liveOutinto1'],
                'name' => '张二',
                'avatar'=>'header.png'
            ],
            [
                'screen_id'=>2,
                'timestamp'=>strtotime('2020-09-27 17:50:00'),
                'screen'=>['camera_position'=>'liveOut1'],
                'name' => '张二',
                'avatar'=>'header.png'
            ],

    ];
        return $record_list;
    }

}









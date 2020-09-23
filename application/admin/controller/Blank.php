<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/23
 * Time: 15:38
 */
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
<?php
/**
 * 项目基础类
 */
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class BaseProject extends Controller
{
    /**
     * 渲染数据初始化数据
     */
    protected function doFetch()
    {
        // 设置初始化数据
        $this->setInitializedData();
        return $this->fetch();
    }
}

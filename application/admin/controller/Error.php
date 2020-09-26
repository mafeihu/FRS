<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use FunctionClass;
class Error extends Controller
{
  public function index()
  {
      return redirect('login/index');
  }
}
<?php

namespace app\dbenx\controller;

use app\dbenx\module\arctype;
use app\dbenx\service\StaticHtml;
use think\admin\Controller;
use think\facade\Route;
use think\facade\View;
class category extends Controller
{
    public function index()
    {
        echo 11;
        $rs=arctype::mk()->where(['ishidden'=>0])->select()->toArray();
      // var_dump($rs);
        foreach ($rs as $key=>$val){
             // var_dump($val);
         //  StaticHtml::instance()->getList($val);
        }
    }

    public function getlist($id = '')
    {
        $rs=arctype::mk()->where(['ishidden'=>0])->select()->toArray();
        foreach ($rs as $key=>$val){
            //判断静态界面是否存在
            $this->beforeBuild($val);
            $html = View::fetch('category/index');
            $this->afterBuild($html);
            // var_dump($val);
            //  StaticHtml::instance()->getList($val);
        }






        //生成静态界面


    }

}
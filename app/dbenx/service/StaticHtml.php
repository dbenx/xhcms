<?php

namespace app\dbenx\service;

use think\admin\Service;
use think\facade\View;

class StaticHtml extends Service
{

    //静态模板生成目录
    protected $staticHtmlDir = "";

    //静态文件
    protected $staticHtmlFile = "";

    //判断是否存在静态
    public function beforeBuild($param)
    {

        var_dump($param);
        //生成静态
        $this->staticHtmlDir = "./html/" .  $this->app->request->controller(true).'/';

        //参数md5
       // $param = md5(json_encode($param));

        $res = $this->staticHtmlFile = $this->staticHtmlDir . $this->app->request->action() . '_' . $param['id'] . '.html';

        //目录不存在,则创建
        if (!file_exists($this->staticHtmlDir)) {
            mkdir($this->staticHtmlDir);

        }

        //静态文件存在,并且没有过期
        if (file_exists($this->staticHtmlFile)) {
          //  header("Location:/" . $this->staticHtmlFile);
            exit();
        }

    }

    //开始生成静态文件
    public function afterBuild($html)
    {

        if (!empty($this->staticHtmlFile) && !empty($html)) {
            if (file_exists($this->staticHtmlFile)) {
                \unlink($this->staticHtmlFile);
            }
            if (file_put_contents($this->staticHtmlFile, $html)) {
              //  header("Location:/" . $this->staticHtmlFile);
              //  exit();
            }
        }

    }


    public function getList($var=[])
    {
        var_dump($var['id']);
        //判断静态界面是否存在
        $this->beforeBuild($var);




        $html = View::fetch('category/index');
        $this->afterBuild($html);
    }
}
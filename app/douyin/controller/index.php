<?php

namespace app\douyin\controller;

use app\douyin\model\DouyinInfo;
use app\douyin\service\GetDouyinPl;
use think\admin\Controller;

class index extends Controller
{

    public function index()
    {

        DouyinInfo::mQuery()->page();

    }

    public function cs()
    {
        $message = 1000;
        $page = ceil($message / 20);
        // echo $page;
        $example = function () use ($page) {
            for ($i = 0; $i < $page; $i++) {
                $rs = GetDouyinPl::instance()->get('7162117911291759902', $i * 20);
                //  var_dump($rs);
                foreach ($rs as $val) {
                    echo $val['cid'];
                    // echo 123;
                    /**
                     * $dd[]=
                     **/
                if(DouyinInfo::mk()->where(['cid'=>$val['cid'],'aweme_id'=>$val['aweme_id']])->count('id')>0){
                }else{
                    DouyinInfo::mk()->insert([
                        'cid' => $val['cid'],
                        'aweme_id' => $val['aweme_id'],
                        'digg_count' => $val['digg_count'],
                        'text' => $val['text'],
                        'ip_label' => $val['ip_label'],
                        'nickname' => $val['user']['nickname'],
                        'avatar_thumb' => $val['user']['avatar_thumb']['url_list'][0],
                        'sec_uid' => $val['user']['sec_uid'],
                        'uid' => $val['user']['uid'],
                        'create_time' => $val['create_time']
                    ]);
                }
                 echo $val['user']['nickname'] . '--' . $val['ip_label'] . '--' . $val['text'] . '<br>';
                }
                unset($rs);
            }
        };
        echo $example();
    }
}
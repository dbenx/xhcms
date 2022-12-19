<?php

namespace app\douyin\controller;

use app\douyin\model\DouyinKeylable;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

class keylable extends Controller
{
    public  function index(){
        DouyinKeylable::mQuery()->layTable(function () {
            $this->title = '标签管理';
        }, function (QueryHelper $query) {
            $query->like('title');
        });
    }

}
<?php

namespace app\douyin\controller;

use app\douyin\model\DouyinVideo;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\model\SystemUser;
use think\facade\Config;

class video extends Controller
{

    public function index()
    {
        DouyinVideo::mQuery()->layTable(function () {
            $this->title = '视频管理中心';
        }, function (QueryHelper $query) {
            $query->like('title');
        });
    }

    public function add()
    {
        $this->marks = Config::get('source')['region'];
        DouyinVideo::mForm('form');
    }

    public function edit()
    {
        $this->marks = Config::get('source')['region'];
        DouyinVideo::mForm('form');
    }

    protected function _form_filter(array &$data)
    {
        if ($this->request->isPost()) {
            if(isset($data['region'])){
                $data['region'] = arr2str($data['region']);
            }else{
                $data['region'] ='';
            }
        } else {

            $data['region']=str2arr($data['region']);
        }
    }

    /**
     * 修改用户状态
     * @auth true
     */
    public function state()
    {
        DouyinVideo::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }
}
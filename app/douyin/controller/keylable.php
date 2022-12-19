<?php

namespace app\douyin\controller;

use app\douyin\model\DouyinKeylable;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

class keylable extends Controller
{
    /**
     * 标签管理
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function index()
    {
        DouyinKeylable::mQuery()->layTable(function () {
            $this->title = '标签管理';

        }, function (QueryHelper $query) {
            $query->like('title');
        });
    }

    /**
     * 添加信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function add()
    {
        DouyinKeylable::mForm('form');
    }

    /**
     * 修改信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function edit()
    {
        DouyinKeylable::mForm('form');
    }


    protected function _form_filter(array &$data)
    {
        if ($this->request->isPost()) {
            [$state, $rep, $error] = [0, 0, 0];
            if ($data['name'] == '') $this->error('数据不能为空！');
            $url = explode(PHP_EOL, $data['name']);
            foreach ($url as $vo) {
                if ($vo != '') {
                    if (DouyinKeylable::mk()->where(['title' => $vo, 'uid' => session('user.id'), 'is_deleted' => 0])->field('id')->count() > 0) {
                        $rep++;
                    } else {
                        $insdata[] = array(
                            'uid' => session('user.id'),
                            'title' => trim($vo)
                        );
                    }
                }
            }
            if (!empty($insdata)) {
                $state = DouyinKeylable::mk()->insertAll($insdata);
            }
            $this->success('本次更新' . $state . '条信息，重复信息' . $rep . '条,错误' . $error . '条');
        }
    }
}
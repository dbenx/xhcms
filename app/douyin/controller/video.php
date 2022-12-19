<?php

namespace app\douyin\controller;

use app\douyin\model\DouyinKeylable;
use app\douyin\model\DouyinVideo;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\facade\Config;

class video extends Controller
{

    /**
     * 视频管理中心
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        DouyinVideo::mQuery()->layTable(function () {
            $this->title = '视频管理中心';
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
        $this->marks = Config::get('source')['region'];
        DouyinVideo::mForm('form');
    }

    /**
     * 编辑信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function edit()
    {
        $this->marks = Config::get('source')['region'];
        DouyinVideo::mForm('form');
    }

    protected function _form_filter(array &$data)
    {
        if ($this->request->isPost()) {
            if (!isset($data['key_label'])) $this->error('请选择关键词标签');
            if (isset($data['region'])) {
                $data['region'] = arr2str($data['region']);
            } else {
                $data['region'] = '';
            }
            $data['key_label'] = arr2str($data['key_label']);
        } else {
            $this->lables = DouyinKeylable::mk()->where(['uid' => session('user.id'), 'status' => 1, 'is_deleted' => 0])->column('id,title');
            $data['region'] = str2arr($data['region']);

        }
    }

    /**
     * 表单结果处理
     * @param bool $state
     * @return void
     */
    protected function _form_result(bool $state)
    {
        if ($state) {
            $this->success('数据更新成功', "javascript:history.back()");
        }
    }

    /**
     * 修改状态
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function state()
    {
        DouyinVideo::mSave($this->_vali([
            'status.in:0,1' => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }


    /**
     * 删除视频
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function remove()
    {
        DouyinVideo::mDelete();
    }
}
<?php

namespace app\data\controller\api;

use app\data\model\BaseUserMessage;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\model\SystemBase;

/**
 * 基础数据接口
 * Class Data
 * @package app\data\controller\api
 */
class Data extends Controller
{

    /**
     * 1、所有项目数据到一个后台表单，区分合法转化和不合法转化，不合法转化不推送给咨询
     * 2、分配表单给咨询要结合咨询上班时间
     * 3、每个咨询一个账号，可以看到自己分配的表单
     * 4、咨询设置上线，达到上线后不在分配，分配给没有达到上线的咨询
     * 获取指定数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getData()
    {
        $data = $this->_vali(['name.require' => '数据名称不能为空！']);
        $extra = ['about', 'slider', 'agreement', 'cropper']; // 其他数据
        if (in_array($data['name'], $extra) || isset(SystemBase::items('页面内容')[$data['name']])) {
            $this->success('获取数据对象', sysdata($data['name']));
        } else {
            $this->error('获取数据失败', []);
        }
    }

    /**
     * 图片内容数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSlider()
    {
        $this->keys = input('keys', '首页图片');
        if (isset(SystemBase::items('图片内容')[$this->keys])) {
            $this->success('获取图片内容', sysdata($this->keys));
        } else {
            $this->error('获取图片失败', []);
        }
    }

    /**
     * 系统通知数据
     */
    public function getNotify()
    {
        BaseUserMessage::mQuery(null, function (QueryHelper $query) {
            if (($id = input('id')) > 0) {
                BaseUserMessage::mk()->where(['id' => $id])->inc('num_read')->update([]);
            }
            $query->equal('id')->where(['status' => 1, 'deleted' => 0]);
            $this->success('获取系统通知', $query->order('sort desc,id desc')->page(true, false, false, 20));
        });
    }
}
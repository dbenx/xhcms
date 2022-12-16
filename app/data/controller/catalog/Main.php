<?php

namespace app\data\controller\catalog;

use think\admin\Controller;
use app\data\model\DataCategory;
use think\admin\helper\QueryHelper;
class Main extends  Controller
{
    /**
     * 网站栏目管理
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function index()
    {
        DataCategory::mQuery()->layTable(function () {
            $this->title = '网站栏目管理';
            $this->type = input('get.type', 'index');
        }, function (QueryHelper $query) {
            $query->dateBetween('create_at')->like('title')->equal('status,id');
        });
    }

    /**
     * 栏目 JSON 表
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function json()
    {
        $rs = DataCategory::mk()->column('*');
        $this->success('获取分类成功',$rs , 0);
    }

    /**
     * 添加栏目
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function addcatalog(){
        $this->title = '网站栏目管理 > 添加栏目';
        DataCategory::mForm('cateedit');
    }

    /**
     * 编辑栏目
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function editcatalog(){
        $this->title = '网站栏目管理 > 编辑栏目';
        DataCategory::mForm('cateedit');
    }
    /**
     * 表单数据处理
     * @param array $vo
     * @throws \ReflectionException
     */
    protected function _form_filter(array &$vo)
    {
        if ($this->request->isGet()) {
            /* 选择自己的上级菜单 */
            $vo['rootid'] = $vo['rootid'] ?? input('rootid', '');
            $vo['id'] = $vo['id'] ?? input('id', '');
            $menus = DataCategory::mk()->order('id asc,sortnum desc')->column('id,rootid,name,title', 'id');
            $this->menus=$this->_selectTree($menus);
        }
    }

    /**
     * @param $param
     * @param int $pid
     * @param int $lvl
     * @return array
     * 树型菜单处理
     */
    protected function _selectTree($param, $pid = 0, $lvl = 0)
    {
        static $res = [];
        foreach ($param as $key => $vo) {
            if ($pid == $vo['rootid']) {
                $vo['name'] = str_repeat('ㅤ├ㅤ', $lvl) . $vo['name'];
                $res[] = $vo;
                $temp = $lvl + 1;
               $this->_selectTree($param, $vo['id'], $temp);
            }
        }
        return $res;
    }


}
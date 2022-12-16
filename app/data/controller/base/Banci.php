<?php

namespace app\data\controller\base;

use app\data\model\DataBanci;
use think\admin\Controller;


/**
 * 班次
 */
class Banci extends Controller
{
    /**
     * 班次信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function index(){
        $this->title='班次管理';

        DataBanci::mQuery()->where(['deleted'=>0])->page();

    }

    /**
     * 添加班次
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function  add(){
        DataBanci::mForm('form');
    }
    /**
     * 编辑班次
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function  edit(){
        DataBanci::mForm('form');
    }
    /**
     * 删除班次
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function remove(){
        DataBanci::mDelete();
    }


}
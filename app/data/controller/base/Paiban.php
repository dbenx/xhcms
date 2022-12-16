<?php

namespace app\data\controller\base;

use app\data\model\DataBanci;
use app\data\model\DataPaiban;
use think\admin\Controller;
use think\admin\model\SystemUser;

/**
 * 排班管理
 */
class Paiban extends Controller
{
    /**
     * 排班信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function index()
    {
        $this->title = '咨询排班';
        $tabletit = [];
        $list = [];
        $this->type = $this->request->get('type', 'index');
        /*上月*/
        if ($this->type == 'lmonth') {
            $date=$this->_getlastMonthDays(time());
            $stadate = strtotime($date[0]);//开始时间
            $enddate = strtotime($date[1]);//结束时间
        } else if ($this->type == 'nmonth') {
            $date=$this->_getNextMonthDays(time());
            $stadate = strtotime($date[0]);//开始时间
            $enddate = strtotime($date[1]);//结束时间
        } else {
            $stadate = strtotime(date('Y-m-1'));//开始时间
            $enddate = strtotime(date('Y-m-'.date('t',strtotime(date('Y-m-d')))));//结束时间
        }

        $t = date("t", $stadate);
        $user = SystemUser::mk()->column('username,id');
        foreach ($user as $v) {
            for ($i = 1; $i <= $t; $i++) {
                $rs = ['name' => $v['id'], 'date' => strtotime(date('Y-m-',$stadate) . $i)];
                if (DataPaiban::mk()->where($rs)->count() > 0) {
                } else {
                    DataPaiban::mk()->insert($rs);
                }
            }
        }
        for ($i = 1; $i <= $t; $i++) {
            $tabletit[] = ['date' => date('Y-m-d', strtotime(date('Y-m-',$stadate) . $i)), 'week' => $this->_getTimeWeek(strtotime(date('Y-m-',$stadate) . $i))];
        }
        $rs = DataPaiban::mk()->group('name')->column('name');
        foreach ($rs as $k => $v) {
            $list[] = DataPaiban::mk()->where(['name' => $v])->whereBetweenTime('date', $stadate, $enddate)->column('id,name,mark');
        }
        $this->tabletit = $tabletit;
        $this->list = $list;
        $this->banci = $this->_banci();
        $this->fetch();
    }

    /**
     * 排班管理
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function banci()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id');
            $bc = $this->request->param('bc');
            $rsid = DataPaiban::mk()->where(['id' => $id])->save(
                ['mark' => $bc]
            );
            if ($rsid) {
                $this->success('排班成功');
            } else {
                $this->error('错误！刷新后重试！');
            }
        }
    }

    /**
     * 下月日期
     * @param $timestamp
     * @return array
     */
    protected function _getNextMonthDays($timestamp):array
    {
        $arr = getdate($timestamp);
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
            $lastday = date('Y-m-'.date("t", strtotime($firstday)), strtotime("$firstday +1 month -1 day"));
        }
        return array($firstday, $lastday);
    }

    /**
     * 上月的日期
     * @param $date
     * @return array
     */
    protected function _getlastMonthDays($timestamp):array
    {

        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-'.date("t", strtotime($firstday)), strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }

    protected function _getTimeWeek($time, $i = -1)
    {
        $weekarray = array("一", "二", "三", "四", "五", "六", "日");
        $oneD = 24 * 60 * 60;
        return "周" . $weekarray[date("w", $time + $oneD * $i)];
    }

    protected function _banci()
    {
        return DataBanci::mk()->where(['deleted' => 0])->order('time')->column('id,name');
    }


}
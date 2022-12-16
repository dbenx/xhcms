<?php

namespace app\data\controller\base;

use app\data\model\DataBanci;
use app\data\model\DataFyinfo;
use app\data\model\DataFylog;
use app\data\model\DataPaiban;
use app\wechat\service\WechatService;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\model\SystemUser;
use  WeChat;

/**
 *飞鱼表单中心
 */
class Info extends Controller
{
    protected $pczx = ['10000', '10003'];

    /**
     * 飞鱼表单查看自己信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */

    public function index()
    {
        DataFyinfo::mQuery()->layTable(function () {
            $this->title = '飞鱼表单';
        }, function (QueryHelper $query) {
            $query->where(['uid' => session('user.id')]);
            $query->equal('convert_status')->order('id desc')->like('telphone|weixin|qq#phone,name,adv_name,ad_name');
            $query->dateBetween('create_at');
        });
    }
    /**
     * 飞鱼表单-查看全部信息
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public  function allinfo(){
        DataFyinfo::mQuery()->layTable(function () {
            $this->title = '飞鱼表单';
        }, function (QueryHelper $query) {
            $query->equal('convert_status')->order('id desc')->like('telphone|weixin|qq#phone,name,adv_name,ad_name');
            $query->dateBetween('create_at');
        });
    }

    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _page_filter(array &$data)
    {
        foreach (SystemUser::mk()->column('id,nickname') as $val) {
            $systemuser[$val['id']] = $val['nickname'];
        }
        foreach ($data as &$vo) {
            if ($vo['uid'] != 0) {
                $vo['uid'] = $systemuser[$vo['uid']];
            } else {
                $vo['uid'] = '未分配';
            }
        }
    }

    /**
     * 二维码拨打电话
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function edit()
    {
        if ($this->app->request->isGet()) {
            $this->username = SystemUser::mk()->where(['is_deleted' => 0])->column('id,nickname');
        }
        DataFyinfo::mForm('edit');
    }

    public function postfy()
    {
        if ($this->request->isPost()) {
            $data = $this->app->request->post(); //接收POST数据
            $uid = 0;
            $upuid = DataFylog::mk()->where('uid', '<>', 0)->order('id desc')->column('uid');//上次分配的UID
            var_dump($upuid);
            $zxuser = json_decode($this->_GetZhiBanzx());//获取值班的咨询
            if (empty($upuid)) {
                $upuid = 0;
            }
            if ($this->_arrayserch($zxuser, $upuid[0])) {
                $upuid = $this->_arrayserch($zxuser, $upuid[0]);
            } else {
                $upuid = $zxuser[0];
            }
            //没有咨询接线，默认不分配
            if (empty($zxuser)) {
                if (DataFyinfo::mk()->where(['fid' => $data['fid'], 'telphone' => $data['telphone']])->count('id') > 0) {
                } else {
                    $data['uid'] = 0;
                    $data['fid'] = $data['id'];
                    unset($data['id']);
                    DataFyinfo::mk()->strict(false)->insertGetId($data);
                    DataFylog::mk()->insertGetId(['uid' => $data['uid'], 'content' => json_encode($data)]);
                    $this->success('更新成功！', '', 0);
                }
            } else {
                $data['uid'] = $upuid;
                $data['fid'] = $data['id'];
                unset($data['id']);
                $rsmaxnum = SystemUser::mk()->where(['id' => $upuid])->column('maxnum')[0];
                $num = DataFyinfo::mk()->where(['create_at' => strtotime(date('Y-m-d'))])->count('id');
                if ($rsmaxnum) {
                    if ($num > $rsmaxnum) {
                        $upuid = $this->_arrayserch($zxuser, $upuid);
                    }
                }
                if (DataFyinfo::mk()->where(['fid' => $data['fid'], 'telphone' => $data['telphone']])->count('id') > 0) {
                } else {
                    if ($data['clue_source'] != 1) {
                        $data['uid'] = 0;
                        DataFyinfo::mk()->strict(false)->insertGetId($data);
                        DataFylog::mk()->insertGetId(['uid' => $data['uid'], 'content' => json_encode($data)]);
                        $this->success('更新成功！', '', 0);
                    } else {
                        $id = DataFyinfo::mk()->strict(false)->insertGetId($data);
                        if ($id) {
                            DataFylog::mk()->insertGetId(['uid' => $data['uid'], 'content' => json_encode($data)]);
                            $this->success('更新成功！', '', 0);
                        }
                    }
                }
            }
        }
    }

    protected function _arrayserch($arr, $curvalue)
    {
        if (($i = array_search($curvalue, $arr)) !== false) {
            if (isset($arr[$i + 1])) {
                //  echo($arr[$i + 1]);
                return $arr[$i + 1];
            } else {
                return $arr[0];
            }
        } else {
            return false;
        }
    }

    public  function  cc(){
        $config = [
            'token'          => 'GllNRhLUuTz5TEtL',
            'appid'          => 'wxf5518c3ed8b8a5d2',
            'appsecret'      => 'c0c489d0fe1a138fbea0288f20f1740c',
            'encodingaeskey' => 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5',
            // 配置商户支付参数（可选，在使用支付功能时需要）
            'mch_id'         => "1235704602",
            'mch_key'        => 'IKI4kpHjU94ji3oqre5zYaQMwLHuZPmj',
            // 配置商户支付双向证书目录（可选，在使用退款|打款|红包时需要）
            'ssl_key'        => '',
            'ssl_cer'        => '',
            // 缓存目录配置（可选，需拥有读写权限）
            'cache_path'     => '',
        ];
        $wechat = new \WeChat\Pay($config);
       var_dump($wechat);
    }

    public function getfeiyuinfo()
    {
        if ($this->request->isPost()) {
            $uid = session('fpuid') ?? 0;
            echo $uid;
            $uid++;
            $this->app->session->set('fpuid', $uid);
            echo $uid;
            exit();
            $rs = json_decode($this->_GetZhiBanzx(), true);

            echo $uid;
            if (is_array($rs)) {
                if ($uid >= count($rs)) $uid = 0;
            }
            $inputid = $rs[$uid] ?? "0";
            $rsmaxnum = SystemUser::mk()->where(['id' => $inputid])->column('maxnum')[0];
            $num = DataFyinfo::mk()->where(['create_at' => strtotime(date('Y-m-d'))])->count('id');
            if ($rsmaxnum) {
                if ($num > $rsmaxnum) {
                    $uid++;
                    if ($uid >= count($rs)) $uid = 0;
                }
            }
            $data = $this->app->request->post(); //接收POST数据
            $data['uid'] = $inputid;
            $data['fid'] = $data['id'];
            unset($data['id']);
            if (DataFyinfo::mk()->where(['fid' => $data['fid'], 'telphone' => $data['telphone']])->count('id') > 0) {
            } else {
                if ($data['clue_source'] != 1) {
                    $data['uid'] = 0;
                    DataFyinfo::mk()->strict(false)->insertGetId($data);
                    $this->success('更新成功！');
                } else {
                    $id = DataFyinfo::mk()->strict(false)->insertGetId($data);
                    if ($id) {
                        $uid++;
                        // setcookie('fpuid', $uid);
                        $this->app->session->set('fpuid', $uid);
                        $this->success('更新成功！');
                    }
                }
            }


        }
    }

    /**
     * 获取在值班的咨询人员
     */
    protected function _GetZhiBanzx()
    {
        $usid = [];
        $rs = DataPaiban::mk()->where(['date' => strtotime(date('Y-m-d'))])->order('name')->column('name,mark');
        foreach ($rs as $val) {
            if ($val['mark'] != 0) {
                $gztime = DataBanci::mk()->where(['id' => $val['mark']])->column('time')[0];
                // var_dump($gztime);
                $gztime = explode(' - ', $gztime);
                if (isset($gztime[0]) && isset($gztime[1])) {
                    if ($this->_checkIsBetweenTime($gztime[0], $gztime[1])) {
                        $usid[] = $val['name'];
                    }
                }
            }
        }
        return json_encode($usid);
    }


    protected function _checkIsBetweenTime($start, $end)
    {
        if ($start || $end) {
            $date = date('H:i');
            $curTime = strtotime($date);//当前时分
            $assignTime1 = strtotime($start);//获得指定分钟时间戳，00:00
            $assignTime2 = strtotime($end);//获得指定分钟时间戳，01:00

            if ($curTime > $assignTime1 && $curTime < $assignTime2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 扫描拨打电话
     * @auth true  # 表示需要验证权限
     * @menu true  # 添加系统菜单节点
     * @login true # 强制登录才可访问
     */
    public function calltel()
    {
        $tel = $this->app->request->get('tel');
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>拨打电话</title></head><body><script>window.location.href='tel:$tel';</script></body></html>";
    }


    public function cs()
    {
        $arr = array("Name", "Age", "41", "Country", "USA");
        var_dump($arr);
    }
}
<?php

namespace app\data\controller\article;

use app\data\model\DataArticle;
use app\data\model\DataCategory;
use think\admin\Controller;
use think\admin\helper\QueryHelper;

/**
 * 文章管理
 */
class Manage extends Controller
{
    public function index()
    {
        $this->type = input('get.type', 'index');
        DataArticle::mQuery()->layTable(function () {
            $this->title = '文章标签管理';
        }, function (QueryHelper $query) {
            $query->where(['status' => 1]);
            $query->where(['status' => intval($this->type === 'index')]);
            $query->like('title')->equal('status')->order('id desc')->dateBetween('postime');
        });
    }


    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _page_filter(array &$data)
    {
        foreach (DataCategory::mk()->column('id,name') as $value){
            $catalog[$value['id']]=$value['name'];
        }
        foreach ($data as &$vo) {
            $vo['cid'] =$catalog[$vo['cid']];
        }

    }

    public function cs(){

        var_dump($rs);
    }







}
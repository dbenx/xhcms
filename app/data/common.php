<?php
if (!function_exists('responses')) {
    function responses($uid)
    {
        return \think\admin\model\SystemUser::mk()->where(['id'=>$uid])->column('nickname')[0];
    }
}
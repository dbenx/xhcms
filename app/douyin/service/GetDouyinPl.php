<?php

namespace app\douyin\service;

use think\admin\Service;

class GetDouyinPl extends Service
{

    public function get($aweme_id,$cursor=0)
    {

        $ts = floor($this->getMillisecond() / 1000);
        $cookie = 'passport_csrf_token=4400fc2d7da5a86c24ecfa8a8524ff3f; passport_csrf_token_default=4400fc2d7da5a86c24ecfa8a8524ff3f; n_mh=6grxceSFCVrRAb57vVgcSLTBT-or_RNntglldUCne0A; session_secure=1; douyin.com; LOGIN_STATUS=1; FOLLOW_LIVE_POINT_INFO=%22MS4wLjABAAAAlKpuO5_90vRWan3MwnawfG0CQVR5A9Kr8-3N4MSH6oQ%2F1670860800000%2F0%2F1670827567594%2F0%22; s_v_web_id=verify_lbkfle7b_Z2aL3ry2_GclE_44cm_9V8D_KgCfYLSygIyl; csrf_session_id=a65d4fa3f829f0ea61ca801bace50a03; ttcid=147eaf5f128a40ff975b0ed7631dcdb821; passport_fe_beating_status=false; odin_tt=736de4d4497047029b022805266da26080a5594418f6c135865725eee6c0b9101f2c018b2dbc81a68a6d97dd1379566511bf934b8d55af01efc966959988777f; sid_guard=5c467d17f5c95cfe28d480cf0373bcef%7C1670827605%7C21600%7CMon%2C+12-Dec-2022+12%3A46%3A45+GMT; uid_tt=bbe22efb13920095650ae0f81d4a1643; uid_tt_ss=bbe22efb13920095650ae0f81d4a1643; sid_tt=5c467d17f5c95cfe28d480cf0373bcef; sessionid=5c467d17f5c95cfe28d480cf0373bcef; sessionid_ss=5c467d17f5c95cfe28d480cf0373bcef; sid_ucp_v1=1.0.0-KDRlZTA0OTViNzNlODc2OTBjZjIxNjIyZmVlMWU1Y2ZlYTVjMTFlMWQKCBDVnNucBhgNGgJsZiIgNWM0NjdkMTdmNWM5NWNmZTI4ZDQ4MGNmMDM3M2JjZWY; ssid_ucp_v1=1.0.0-KDRlZTA0OTViNzNlODc2OTBjZjIxNjIyZmVlMWU1Y2ZlYTVjMTFlMWQKCBDVnNucBhgNGgJsZiIgNWM0NjdkMTdmNWM5NWNmZTI4ZDQ4MGNmMDM3M2JjZWY; ttwid=1%7CpyOKAQIHk8iPXriA_XH-u9vb1adKCZGmckUAGPvaSOw%7C1670827799%7Ca78da5ec4e946bed45def4940650a762b140a3d468faf786fe8257a68794222a; AB_LOGIN_GUIDE_TIMESTAMP=%221670827931605%22; __ac_nonce=06396ddc60033fc887ee; __ac_signature=_02B4Z6wo00f01ayKgogAAIDAz4BC4qnjod2sqoYAAAi1Q6T87tpkTJ9syNHLhcGqUh7Wp5gzqlENtM8wJD2CnRS78Addm5pqGdqVuMbyG9rPY6ZH0m2UCoK32iUdaFMTzK8hcR6ROVLsVRkg91; strategyABtestKey=%221670831685.998%22; msToken=JGS2sqDjFJk-5dliHZJokKR0wobqHPvdNwsbGsx3OhMHofBWbp9kwrN9oRd0sZOMwkfF9HI9ODJTjcu01YKLgdNancWKyvbco7PZ79SfljTEcZZgUeJd_0TepkDRl4k=; home_can_add_dy_2_desktop=%221%22; tt_scid=ttSpWoarR.oTYTxEu1PML-4EdQuUWJ4OgL.ccVcj9HNQ.oufaLcWA.janVirDPOj554c; download_guide=%223%2F20221212%22; msToken=Bh-L4_2W3XO6_-oE8C8kW4mkwXNgHa-0r0Mdgz4WUdfs7Qx3GMXiwc-GRycI1SYLl5SdyP2Vo6xVsYJjcOTw2HYqYvUtZWwj2co9tH7D7amQP-WgksA-SA==';
 $url = 'https://www.douyin.com/aweme/v1/web/comment/list/?device_platform=webapp&aid=6383&channel=channel_pc_web&aweme_id=' . $aweme_id . '&cursor='.$cursor.'&count=20&item_type=0&insert_ids=&rcFT=&pc_client_type=1&version_code=170400&version_name=17.4.0&cookie_enabled=true&screen_width=1920&screen_height=1080&browser_language=zh-CN&browser_platform=Win32&browser_name=Chrome&browser_version=104.0.0.0&browser_online=true&engine_name=Blink&engine_version=104.0.0.0&os_name=Windows&os_version=10&cpu_core_num=8&device_memory=8&platform=PC&downlink=10&effective_type=4g&round_trip_time=50&webid=7175081847058597431';

//$gorgon=getXg($url,$cookie,$ts,$_rticket);
        $headers[] = "referer:https://www.douyin.com/";
        $headers[] = "Connection: keep-alive";
        $headers[] = 'Cookie: ' . $cookie;
        $headers[] = "X-SS-REQ-TICKET: 1588555815435";
        $headers[] = "X-Tt-Token: 00861d0d1876038df131e0fd31895e7a7ec84405d255864de8fb1c72cfba5b43f47abc1d13178401d7eccc11394ed2d84755";
        $headers[] = "sdk-version: 1";
        $headers[] = "x-tt-trace-host: 00-dd4e1bf00a101f48f0e0fa15bc950468-dd4e1bf00a101f48-01";
        $headers[] = "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36";
//$headers[] = "X-Gorgon: ".$gorgon;
        $headers[] = "X-Khronos: " . $ts;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($curl);
        $data = json_decode($data, true);

        return $data['comments'];
    }

   protected function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}
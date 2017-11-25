<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/19
 * Time: 3:16
 */

require_once "StudentInfo.php";
require_once "AipOcr.php";

class cet
{

    public static function cetScore($data)
    {
        //一些基本参数的设置
        $url = "http://www.chsi.com.cn/cet/query";
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0';
        $host = array('Host: www.chsi.com.cn');
        $refer = 'http://www.chsi.com.cn/cet/';


        $id = $data['id'];
        $name = $data['name'];


        if (empty($id) || empty($name))
            AipOcr::resultJson(500, 'the input parameter is wrong', '');


        $post = array(
            'zkzh' => $id,
            'xm' => $name,
        );
        $ch = curl_init();
        //curl请求设置
        $httpPackage = array(
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => $userAgent,
            CURLOPT_HEADER => $host,
            CURLOPT_REFERER => $refer,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post),
            CURLOPT_RETURNTRANSFER => 1
        );
        curl_setopt_array($ch, $httpPackage);
        $str = curl_exec($ch);
        curl_close($ch);

        //获得字符串之后正则处理,这里对正则表达式不熟悉,所以使用了str_replace和explode来处理整个字符串
        $string = '<table border="0" align="center" cellpadding="0" cellspacing="6" class="cetTable">';
        $str = explode($string, $str);

        if (isset($str[1])) {
            $str = explode('</table>', $str[1]);
            $str = preg_replace(array('/<\/?[^>]+>/', '/\r/', '/\n/', '/\t/', '/&nbsp;/', '/ /'), '', $str[0]);
            $keywords = array('姓名：', '学校：', '考试级别：', '笔试成绩准考证号：', '总分：', '听力：', '阅读：', '写作和翻译：', '口试成绩准考证号：', '等级：');
            $str = str_replace($keywords, '.', $str);
            $str = explode('.', $str);
            unset($str[0]);
            $queryResult = new StudentInfo($str);
            AipOcr::resultJson(200, 'success', $queryResult);
        } else {
            //正则若处理错误,则直接500返回
            AipOcr::resultJson(500, 'name or id is wrong', '');
        }
    }


    //通过magicloop的轮子获取学号
    //parameter:openid 微信的openID
    //return:stunum 学号
    public static function getStuNum($openid)
    {
        $url = 'https://wx.idsbllp.cn/MagicLoop/index.php?s=/addon/UserCenter/UserCenter/getStuInfoByOpenId&openId=' . $openid;
        $ch = curl_init();
        $httpPackage = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
        );
        curl_setopt_array($ch, $httpPackage);
        $str = curl_exec($ch);
        curl_close($ch);


        $result = json_decode($str);
        if ($result->status != '200')
            return 0;
        else
            return $result->data->usernumber;
    }

}



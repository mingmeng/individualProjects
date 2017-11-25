<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/19
 * Time: 1:16
 */
require_once "config.php";
class WXdev
{
    const oauthUrl="https://api.weixin.qq.com/sns/oauth2";


    //网页开发获得accesstoken
    public static function getPageAccess_token($code)
    {
        $WXauthorityUrl=self::oauthUrl."/access_token?appid=".config::WXAppID."&secret=".config::WXAppSecret."&code=".$code."&grant_type=authorization_code";
        $ch=curl_init();
        $optionsArr = array(
            CURLOPT_URL => $WXauthorityUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
        );
        curl_setopt_array($ch,$optionsArr);
        $curlJsonResult=curl_exec($ch);
        $userPageInfo=json_decode($curlJsonResult);
        return $userPageInfo;
    }

    //网页开发刷新Access_token 待重写 by mingmeng
    public static function refreshPageAccess_token(){
        $WXauthorityUrl=self::oauthUrl."/refresh_token?appid=".config::WXAppID."&grant_type=refresh_token&refresh_token=REFRESH_TOKEN ";
        $ch=curl_init();
        $optionsArr = array(
            CURLOPT_URL => $WXauthorityUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
        );
        curl_setopt_array($ch,$optionsArr);
        $curlJsonResult=curl_exec($ch);
        $userPageInfo=json_decode($curlJsonResult);
        return $userPageInfo;
    }


    //验证服务器的方法
    public static function checkSignature($get)
    {
        $signature = $get["signature"];
        $timestamp = $get["timestamp"];
        $nonce = $get["nonce"];
        $tmpArr = array(config::Token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($signature == $tmpStr) {
            return true;
        } else {
            return false;
        }
    }
}
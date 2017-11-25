<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/18
 * Time: 23:17
 */
require_once "config.php";
require_once "WXdev.php";
session_start();

if (WXdev::checkSignature($_GET) == true)
    exit($_GET['echostr']);

if (isset($_GET['code'])) {
    $code=$_GET['code'];
    $userPageInfo=WXdev::getPageAccess_token($code);
    if ($userPageInfo->errcode==40029)
        echo 1;
    $openid=$userPageInfo->openid;
    $_SESSION['openid']=$openid;
    $database=config::getConnection();
    $result = $database->query("SELECT * FROM wx.cet_user WHERE `openid`='{$openid}'");
    $fetchResult = $result->fetch(PDO::FETCH_ASSOC);
    if ($result->rowCount() > 0) {
        $jsonResult = new stdClass();
        $jsonResult->name=$fetchResult['name'];
        $jsonResult->examID=$fetchResult['examID'];
        header("location:https://wxapi.yangruixin.com/cet46/cet46/html/loding.html?".urlencode("name=".$jsonResult->name."&examID=".$jsonResult->examID));
    }
    header("location:https://wxapi.yangruixin.com/cet46/cet46/html/aboutMe.html?openid=".$openid);
}




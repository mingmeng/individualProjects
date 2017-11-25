<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/19
 * Time: 0:13
 */
require_once "config.php";
$redirect_url = "https://wxapi.yangruixin.com/index.php";
$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . config::WXAppID . "&redirect_uri=" . urlencode($redirect_url) . "&response_type=code&scope=" . config::scopeType . "#wechat_redirect";
header("location:" . $url);
<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/25
 * Time: 11:24
 */
require_once "config.php";
require_once "AipOcr.php";
session_start();
$database = config::getConnection();
if ($_SERVER['REQUEST_METHOD'] != "POST")
    AipOcr::resultJson(404, 'method is wrong', '');
$openid = $_SESSION['openid'];
$query = $database->query("SELECT * FROM cet_user WHERE openid='{$openid}'");
if ($query->rowCount() <= 0)
    AipOcr::resultJson(500, 'invalid parameter', '');
elseif ($_POST['type'] == '1')
    AipOcr::resultJson(200, 'success', '');

if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['type']))
    AipOcr::resultJson(500, 'invalid parameter', '');
elseif ($_POST['type'] == 2) {
    $update = $database->prepare("UPDATE cet_user SET examID=?,name=? WHERE openid=?");
    $update->bindValue(1, $_POST['id'], PDO::PARAM_STR);
    $update->bindValue(2, $_POST['name'], PDO::PARAM_STR);
    $update->bindValue(3, $openid);
    $result = $update->execute();
    AipOcr::resultJson(200, 'revise success', '');
}



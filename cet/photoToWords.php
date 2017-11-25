<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/17
 * Time: 17:27
 */
require_once 'AipOcr.php';
require_once 'config.php';
require_once "cet.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST')
    AipOcr::resultJson(404, 'method is wrong', '');

$database = config::getConnection();
$openid = $_SESSION['openid'];

$result = $database->query("SELECT * FROM wx.cet_user WHERE `openid`='{$openid}'");
$fetchResult = $result->fetch(PDO::FETCH_ASSOC);
if ($result->rowCount() > 0) {
    $jsonResult = new stdClass();
    $jsonResult->name=$fetchResult['name'];
    $jsonResult->examID=$fetchResult['examID'];
    AipOcr::resultJson(200, 'ini', $jsonResult);
}

$base64picture = str_replace(array(
    'data:image/jpg;base64,',
    'data:image/png;base64,',
    'data:image/jpeg;base64,',
), '', $_POST['picture']);
$binaryFile = base64_decode($base64picture);
$test = new AipOcr(config::APP_ID, config::API_KEY, config::SECRET_KEY);
$result = $test->basicGeneral($binaryFile, array(
    'detect_direction' => 'true'
));
$dataResult = array();


if (empty($result['words_result']))
    AipOcr::resultJson(404, "mmp", 'mmp');
foreach ($result['words_result'] as $value) {
    $tempValue = $value['words'];
    if (preg_match('/姓名:/', $tempValue))
        $dataResult['name'] = str_replace('姓名:', '', $tempValue);
    elseif (preg_match('/准考证号:/', $tempValue))
        $dataResult['examID'] = str_replace('准考证号:', '', $tempValue);
}

if (is_null($dataResult['id'] || is_null($dataResult['name'])))
    AipOcr::resultJson(404, "the photo can`t be recognized", '');
$insert = $database->prepare("INSERT INTO `cet_user` (`openid`,`name`,`examID`,`stunum`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?)");
$insert->bindValue(1, $openid, PDO::PARAM_STR);
$insert->bindValue(2, $dataResult['name'], PDO::PARAM_STR);
$insert->bindValue(3, $dataResult['examID'], PDO::PARAM_STR);
$insert->bindValue(4, 2016210049, PDO::PARAM_INT);
$insert->bindValue(5, time(), PDO::PARAM_INT);
$insert->bindValue(6, time(), PDO::PARAM_INT);
$insert->execute();
$database = null;

AipOcr::resultJson(200, 'success', $dataResult);








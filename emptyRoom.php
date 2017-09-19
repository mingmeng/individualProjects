<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/9/18
 * Time: 22:17
 */
$_GET['week'];
$_GET['weekdayNum'];
$_GET['sectionNum'];
$_GET['buildNum'];
const version = '1.0.0';
const term = '20179';
require_once "result.php";
function result($status, $info, $output, $request)
{
    $data = new result();
    $data->status = $status;
    $data->info = $info;
    $data->version = version;
    $data->data = $output;
    $data->term = term;
    $data->weekdayNum = "{$request['weekdayNum']}";
    $data->buildNum = "{$request['buildNum']}";
    exit(json_encode($data));
}

if (empty($_GET['week']) || empty($_GET['weekdayNum']))
    result(404, 'one of parameter is null', '', $_GET);

switch ($_GET['sectionNum']) {
    case 0:
        $_GET['sectionNum'] = 12;
        break;
    case 1:
        $_GET['sectionNum'] = 34;
        break;
    case 2:
        $_GET['sectionNum'] = 56;
        break;
    case 3:
        $_GET['sectionNum'] = 78;
        break;
    case 4:
        $_GET['sectionNum'] = 90;
        break;
    case 5:
    case 6:
        $_GET['sectionNum'] = 'ab';
        break;
    default:
        result(404, 'wrong parameter', '', $_GET);
        break;
}


$url = "http://jwzx.cqupt.edu.cn/jwzxTmp/showEmptyRoomResult.php?zc=" . $_GET['week'] . "&xq=" . $_GET['weekdayNum'] . "&sd=" . $_GET['sectionNum'];
$conn = curl_init($url);
$useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
$configArray = array(
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => $useragent,
    CURLOPT_RETURNTRANSFER => 1,
);
curl_setopt_array($conn, $configArray);
$result = curl_exec($conn);
$httpCode = curl_getinfo($conn, CURLINFO_HTTP_CODE);
if ($httpCode != 200)
    result(500, 'spider or server is in trouble', '', $_GET);
$result = str_replace(array('target=_blank', '</a>', '/'), '', $result);
$parseResult = preg_replace(array('/(\s)href=[^\s]*/'), '', $result);
$parseResult = str_replace(array(' ', '<tr>', '<td>', "<tableclass='printTable'>", "<table>"), '', $parseResult);
$parseResult = explode('<a>', $parseResult);
$queryResult = array(
    '2' => array(),
    '3' => array(),
    '4' => array(),
    '5' => array(),
    '8' => array()
);
foreach ($parseResult as $key => $value) {
    if (!empty($value) && ctype_digit($value[0])) {
        $temp = explode('(', $value);
        $temp = explode('（', $temp[0]);
        //array_push($queryResult, $temp[0]);
        switch ($value[0]) {
            case 2:
                array_push($queryResult['2'], $temp[0]);
                break;
            case 3:
                array_push($queryResult['3'], $temp[0]);
                break;
            case 4:
                array_push($queryResult['4'], $temp[0]);
                break;
            case 5:
                array_push($queryResult['5'], $temp[0]);
                break;
            case 8:
                array_push($queryResult['8'], $temp[0]);
                break;
            default:
                result(404, 'wrong building', '', $_GET);
                break;
        }
    }
}
//$parseResult=preg_replace('/(\s)href=[^\s]*/','',$result);

//$xmlParse=xml_parser_create();
//xml_parse_into_struct($xmlParse,$result,$parseResult);
//xml_parser_free($xmlParse);
//var_dump($queryResult);
//result(200, 'success', $queryResult, $_GET);
result(200,'success',$queryResult[$_GET['buildNum']],$_GET);

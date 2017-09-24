<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/9/20
 * Time: 20:13
 */
//$url = "http://jwzx.cqupt.edu.cn/jwzxTmp/showEmptyRoomResult.php?zc=" . $_GET['week'] . "&xq=" . $_GET['weekdayNum'] . "&sd=" . $_GET['sectionNum'];


$classroom['2'] = array(
    "2100",
    "2101",
    "2102",
    "2105",
    "2106",
    "2107",
    "2108",
    "2109",
    "2115",
    "2116",
    "2117",
    "2201",
    "2202",
    "2205",
    "2206",
    "2207",
    "2208",
    "2209",
    "2214",
    "2215",
    "2216",
    "2217",
    "2301",
    "2302",
    "2305",
    "2306",
    "2308",
    "2309",
    "2310",
    "2311",
    "2314",
    "2315",
    "2316",
    "2401",
    "2402",
    "2405",
    "2406",
    "2408",
    "2409",
    "2410",
    "2411",
    "2414",
    "2415",
    "2416",
    "2503",
    "2506",
    "2507",
    "2508",
    "2509",
    "2510",
    "2511",
);
$classroom['3'] = array(
    "3101",
    "3102",
    "3103",
    "3104",
    "3105",
    "3106",
    "3107",
    "3108",
    "3109",
    "3110",
    "3111",
    "3201",
    "3202",
    "3203",
    "3204",
    "3205",
    "3206",
    "3207",
    "3208",
    "3209",
    "3210",
    "3211",
    "3212",
    "3301",
    "3302",
    "3303",
    "3304",
    "3305",
    "3306",
    "3307",
    "3308",
    "3317",
    "3401",
    "3402",
    "3403",
    "3404",
    "3405",
    "3406",
    "3407",
    "3408",
    "3419",
    "3501",
    "3502",
    "3503",
    "3504",
    "3505",
    "3506",
    "3507",
    "3508",
    "3515"
);
$classroom['4'] = array(
    "4101",
    "4102",
    "4103",
    "4104",
    "4105",
    "4106",
    "4107",
    "4201",
    "4202",
    "4203",
    "4204",
    "4205",
    "4206",
    "4207",
    "4208",
    "4209",
    "4210",
    "4211",
    "4212",
    "4213",
    "4214",
    "4215",
    "4216",
    "4217",
    "4301",
    "4302",
    "4303",
    "4304",
    "4305",
    "4306",
    "4307",
    "4308",
    "4309",
    "4310",
    "4311",
    "4312",
    "4313",
    "4314",
    "4315",
    "4316",
    "4317",
    "4401",
    "4402",
    "4403",
    "4404",
    "4405",
    "4406",
    "4407",
    "4408",
    "4409",
    "4410",
    "4411",
    "4412",
    "4413",
    "4414",
    "4415",
    "4416",
    "4417",
    "4501",
    "4502",
    "4503",
    "4504",
    "4505",
    "4506",
    "4507",
    "4508",
    "4509",
    "4510",
    "4511",
    "4512",
    "4513",
    "4514",
    "4515",
    "4516",
    "4517",
    "4601",
    "4602",
    "4603",
    "4604",
    "4605",
    "4606",
    "4607",
    "4608",
    "4609"
);
$classroom['5'] = array(
    "5200",
    "5201",
    "5202",
    "5203",
    "5204",
    "5205",
    "5300",
    "5304",
    "5305",
    "5313",
    "5401",
    "5402",
    "5403",
    "5404",
    "5405",
    "5406",
    "5413",
    "5601",
    "5602");
$classroom['8'] = array("8111",
    "8112",
    "8113",
    "8114",
    "8116",
    "8121",
    "8122",
    "8123",
    "8124",
    "8131",
    "8132",
    "8133",
    "8134",
    "8141",
    "8142",
    "8143",
    "8144",
    "8151",
    "8152",
    "8211",
    "8212",
    "8213",
    "8214",
    "8221",
    "8222",
    "8223",
    "8224",
    "8231",
    "8232",
    "8233",
    "8234",
    "8251",
    "8252",
    "8321",
    "8322",
    "8323",
    "8324",
    "8331",
    "8332");
$classroom = array_merge($classroom['2'], $classroom['3'], $classroom['4'], $classroom['5'], $classroom['8']);
sort($classroom);

const version = '1.0.0';
const term = '20179';

function result($status, $info, $output, $request)
{
    $data = new \stdClass();
    $data->status = $status;
    $data->info = $info;
    $data->version = version;
    $data->data = $output;
    $data->term = term;
    $data->weekdayNum = "{$request['weekdayNum']}";
    $data->buildNum = "{$request['buildNum']}";
    exit(json_encode($data));
}


$mapping = array(
    array(1, 2),
    array(3, 4),
    array(5, 6),
    array(7, 8),
    array(9, 10),
    array(11, 12)
);
require_once "result.php";
require_once "database.php";
set_time_limit(0);

$resultSet = array();
$conn = new PDO(database::linkConfig, database::userName, database::password);
$insert = $conn->prepare("INSERT INTO `cyxbsmobile_emptyroom` (`classroom`,`week`,`weekday`,`timeBucket`,`building_num`) VALUES (?,?,?,?,?)");

foreach ($classroom as $eachValue) {
    $url = "http://jwzx.cqupt.edu.cn/jwzxtmp/kebiao/kb_room.php?room=" . $eachValue;
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


    $result = explode('<tbody>', $result);
    $result = $result[count($result) - 1];
    $result = explode('</tbody>', $result);
    $result = $result[0];


    preg_match_all('/<tr.*(?=>)(.|\n)*?<\/tr>/', $result, $match);
    $match = $match[0];
    $timeResult = array();
    foreach ($match as $value) {
        $value = str_replace('</td>', '', $value);
        $value = explode('<td>', $value);
        if (count($value) == 4)
            array_push($timeResult, $value[2]);
        else
            array_push($timeResult, $value[1]);
    }


    $temp = array();
    $i = 0;
    foreach ($timeResult as $value) {
        $temp[$i] = array();
        if (strpos($value, '第') == false || strpos($value, '节') == false)
            continue;
        $value = explode('第', $value);
        $temp[$i]['weekday'] = getWeekday($value[0]);
        $value = explode('节', $value[1]);
        $temp[$i]['timeBucket'] = getTimeBucket($value[0]);
        $temp[$i]['week'] = getWeek($value[1]);
        $i++;
    }

    $test = array();
    foreach ($temp as $record) {
        for ($i = 0; $i < count($record['week']); $i++) {
            for ($j = 0; $j < count($record['timeBucket']); $j++) {
                array_push($test, array(
                    'classroom' => $eachValue,
                    'week' => $record['week'][$i],
                    'weekday' => $record['weekday'],
                    'timeBucket' => $record['timeBucket'][$j]
                ));
                $insert->bindValue(1, $eachValue, PDO::PARAM_INT);
                $insert->bindValue(2, $record['week'][$i], PDO::PARAM_INT);
                $insert->bindValue(3, $record['weekday'], PDO::PARAM_INT);
                $insert->bindValue(4, $record['timeBucket'][$j], PDO::PARAM_INT);
                $insert->bindValue(5, ($eachValue - $eachValue % 1000) / 1000);
                $insert->execute();
            }
        }
    }

//    $isSetFlag = 0;
//    foreach ($temp as $value) {
//        if ($_GET['weekdayNum'] == $value['weekday']
//            && in_array($mapping[$_GET['sectionNum']][0], $value['timeBucket'])
//            && in_array($mapping[$_GET['sectionNum']][1], $value['timeBucket'])
//            && in_array($_GET['week'], $value['week'])) {
//            $isSetFlag = 1;
//            break;
//        }
//    }
//    if ($isSetFlag == 0)
//        array_push($resultSet, $eachValue);
}


//result(200, 'success', $resultSet, $_GET);

<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/9/23
 * Time: 15:43
 */
require_once "result.php";
require_once "database.php";
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


$_GET['week'];
$_GET['buildNum'];
$_GET['sectionNum'];
$_GET['weekdayNum'];

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

$mapping = array(
    array(1, 2),
    array(3, 4),
    array(5, 6),
    array(7, 8),
    array(9, 10),
    array(11, 12)
);

$resultSet = array();
$conn = new PDO(database::linkConfig, database::userName, database::password);
$select = $conn->prepare("SELECT DISTINCT (`classroom`) FROM `cyxbsmobile_emptyroom` WHERE `building_num`=? AND `weekday`=?  AND `week`=? AND `timeBucket`=?");
$select->bindValue(1, (int)$_GET['buildNum'], PDO::PARAM_INT);
$select->bindValue(2, (int)$_GET['weekdayNum'], PDO::PARAM_INT);
$select->bindValue(4, $mapping[$_GET['sectionNum']][0], PDO::PARAM_INT);
$select->bindValue(3, (int)$_GET['week'], PDO::PARAM_INT);
$select->execute();
$result = $select->fetchAll(PDO::FETCH_NUM);
for($i=0;$i<count($result);$i++){
    $result[$i]=$result[$i][0];
}
$result=array_diff($classroom[$_GET['buildNum']],$result);
sort($result);
result(200,'success',$result,$_GET);


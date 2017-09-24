<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/9/17
 * Time: 18:50
 * @property string version
 */
class result
{
    public $status;
    public $info;
    public $data;
}


class timeSet
{
    public $weekday;
    public $timeBucket;
    public $week;
}

function getWeekday($string)
{
    $result = str_replace(' ', '', $string);

    $result = str_split($result);

    return (int)$result[count($result) - 1];
}

function getTimeBucket($string)
{
    $temp = explode('-', $string);
    $result = array();
    for ($i = (int)$temp[0]; $i <= (int)$temp[count($temp) - 1]; $i++) {
        array_push($result, $i);
    }
    return $result;
}

function getWeek($string)
{
    $string = str_replace(' ', '', $string);
    if (strpos($string, ',') != false) {
        $individualWeeks = explode(',', $string);
        $temp = array();
        foreach ($individualWeeks as $value) {
            $temp = array_merge($temp, getWeekResult($value));
        }
        sort($temp);
        return $temp;
    } else
        return getWeekResult($string);
}

function getWeekResult($string)
{
    if (strpos($string, '单周') != false) {
        $result = str_replace(array('单周', '周'), '', $string);
        $result = getTimeBucket($result);
        for ($i = 0; $i <= count($result); $i++) {
            if ($result[$i] % 2 == 0)
                unset($result[$i]);
        }
        sort($result);
        return $result;
    } elseif (strpos($string, '双周') != false) {
        $result = str_replace(array('单周', '周'), '', $string);
        $result = getTimeBucket($result);
        for ($i = 0; $i <= count($result); $i++) {
            if ($result[$i] % 2 != 0)
                unset($result[$i]);
        }
        sort($result);
        return $result;
    } else {
        $result = str_replace('周', '', $string);
        if (strpos($string, '-') == false)
            return array((int)$result);
        else
            return getTimeBucket($result);
    }
}

//getWeek('3-9周');
//getWeek('6周');



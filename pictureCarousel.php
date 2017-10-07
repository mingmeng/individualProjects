<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/9/17
 * Time: 12:02
 *
 * @request parameters
 * pic_num:请求图片数量
 *
 * @response parameters
 * pictureUrl:图片链接
 * pictureGoToUrl:图片跳转链接
 * keyword:图片关键字
 */

require_once "database.php";
require_once "result.php";

$data = new result();
$databaseConnection = new PDO(database::linkConfig, database::userName, database::password);

if ($_SERVER['REQUEST_METHOD']=="POST") {
    try {

        if ($_POST['pic_num'] == null) {
            $data->status = 404;
            $data->info = "null request parameter";
            $data->data = array();
            exit(json_encode($data));
        } else if (!is_numeric($_POST['pic_num']) || $_POST['pic_num'] <= 0) {
            $data->status = 404;
            $data->info = "wrong request parameter";
            $data->data = array();
            exit(json_encode($data));
        }

        $prepareQuery = $databaseConnection->prepare("SELECT `picture_url`,`picture_goto_url`,`keyword` FROM `picture` WHERE `state`!=0 ORDER BY `update_time` DESC LIMIT 0,?");
        $prepareQuery->bindValue(1, (int)$_POST['pic_num'], PDO::PARAM_INT);
        $prepareQuery->execute();
        $result = $prepareQuery->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        while ($i < count($result)) {
            if ($result[$i]['picture_goto_url']=='')
                $result[$i]['picture_goto_url']=null;
            $data->data[$i] = $result[$i];
            $i++;
        }

        /**
         * 正常结果
         * */
        $data->status = 200;
        $data->info = "success";
        exit(json_encode($data));

    } catch (PDOException $e) {
        echo $e->getTrace();
    }
} elseif (isset($_POST['update_pic'])||$_POST['update_pic'] == 1) {
    if ($_POST['pic_url'] == null || $_POST['pic_goto_url']==null || $_POST['keyword'] == null) {
        $data->status = 404;
        $data->info = "infomation is not as enough";
        $data->data = array();
    } else {
        $state=1;
        $query=$databaseConnection->prepare("INSERT INTO `picture` (`picture_url`,`picture_goto_url`,`keyword`,`state`,`update_time`) VALUES (?,?,?,?,?)");
        $query->bindValue(1,$_POST['pic_url'],PDO::PARAM_STR);
        $query->bindValue(2,$_POST['pic_goto_url'],PDO::PARAM_STR);
        $query->bindValue(3,$_POST['keyword'],PDO::PARAM_STR);
        $query->bindValue(4,$state,PDO::PARAM_INT);
        $query->bindValue(5,time(),PDO::PARAM_INT);
        $query->execute();
        $data->status=200;
        $data->info="success";
        $data->data=array();
    }
    exit(json_encode($data));
} else{
    $data->status = 404;
    $data->info = "null parameter!";
    $data->data = array();
}



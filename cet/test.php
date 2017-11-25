<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/20
 * Time: 1:24
 */

$postInfo=file_get_contents("php://input");
var_dump($postInfo);
exit("http://www.baidu.com");


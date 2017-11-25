<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2017/11/19
 * Time: 3:19
 */


class StudentInfo
{
    public $name;
    public $school;
    public $level;
    public $examID;
    public $sumScore;
    public $listening;
    public $reading;
    public $writing;
    public $speakExamID;
    public $speakExamLevel;

    public function __construct($str = array())
    {
        $this->name = $str[1];
        $this->school = $str[2];
        $this->level = $str[3];
        $this->examID = $str[4];
        $this->sumScore = $str[5];
        $this->listening = $str[6];
        $this->reading = $str[7];
        $this->writing = $str[8];
        $this->speakExamID = $str[9];
        $this->speakExamLevel = $str[10];
    }
}
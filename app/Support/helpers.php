<?php
function do_grade($score)
{
    $grade = 1;
    if ($score > 90) {
        $grade = 1;
    } elseif ($score >= 80) {
        $grade = 2;
    } elseif ($score >= 70) {
        $grade = 3;
    } elseif ($score >= 60) {
        $grade = 4;
    } elseif ($score >= 50) {
        $grade = 5;
    } elseif ($score >= 40) {
        $grade = 6;
    } elseif ($score >= 30) {
        $grade = 7;
    } elseif ($score >= 20) {
        $grade = 8;
    } else {
        $grade = 9;
    }

    return $grade;
}

function do_remarks($grade)
{
    $remark = '';
    if (((int)$grade == 2) || ((int)$grade == 1)) {
        $remark = 'Distinction';
    } elseif (((int)$grade == 3) || ((int)$grade == 4)) {
        $remark = "Credit";
    } elseif (((int)$grade == 5) || ((int)$grade == 6)) {
        $remark = "Pass";
    } elseif ((int)$grade == 7) {
        $remark = "Refered";
    } else {
        $remark = "Fail";
    }

    return $remark;
}

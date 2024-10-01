<?php
function calculate_age(string $date):Int{
    $birth = date('Y-m-d', strtotime($date));
    $age = date("Y") - date('Y', strtotime($date));
    if(date('m', strtotime($date)) > date("m")) $age -= 1;
    else if(date('m', strtotime($date)) == date("m") && date('d', strtotime($date)) > date("d")) $age -= 1;
    return $age;
}
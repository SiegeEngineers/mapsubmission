<?php


$categories = [
    ['name' => 'Competitiveness', 'min' => 0, 'max' => 20],
    ['name' => 'Complexity', 'min' => 0, 'max' => 10],
    ['name' => 'Originality', 'min' => 0, 'max' => 10],
    ['name' => 'Visual Appeal', 'min' => 0, 'max' => 5],
    ['name' => 'Clever use of “holy” in the gameplay', 'min' => 0, 'max' => 5]
];


function toKey($string)
{
    return preg_replace("/[^A-Za-z]/", "", $string);
}

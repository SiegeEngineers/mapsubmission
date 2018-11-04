<?php


$categories = [
    ['name' => 'Originality', 'min' => 0, 'max' => 10],
    ['name' => 'Professionality', 'min' => 0, 'max' => 10],
    ['name' => 'Balance & Playability', 'min' => 0, 'max' => 10],
    ['name' => 'Theme', 'min' => 0, 'max' => 10],
    ['name' => 'Visual Appeal', 'min' => 0, 'max' => 10]
];


function toKey($string)
{
    return preg_replace("/[^A-Za-z]/", "", $string);
}

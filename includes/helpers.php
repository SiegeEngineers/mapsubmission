<?php


$categories = [
    ['name' => 'Originality', 'min' => 0, 'max' => 4],
    ['name' => 'Professionality', 'min' => 0, 'max' => 2],
    ['name' => 'Balance & Playability', 'min' => 0, 'max' => 4],
    ['name' => 'Theme', 'min' => 0, 'max' => 4],
    ['name' => 'Visual Appeal', 'min' => 0, 'max' => 4]
];


function toKey($string)
{
    return preg_replace("/[^A-Za-z]/", "", $string);
}
